<?php

namespace App\Controllers;

use App\Models\TestModel;
use App\Models\QuestionModel;
use App\Models\QuestionOptionModel;
use App\Services\AiService;

class AdminAptitudeController extends BaseController
{
    public function index()
    {
        $testModel = new TestModel();
        $db = \Config\Database::connect();

        $tests = $testModel->findAll();
        
        // Count actual questions for each test
        foreach ($tests as &$t) {
            $testId = is_array($t) ? $t['id'] : $t->id;
            $count = $db->table('questions')->where('test_id', $testId)->countAllResults();
            if (is_array($t)) {
                $t['question_count'] = $count;
            } else {
                $t->question_count = $count;
            }
        }

        return view('admin/aptitude/index', ['tests' => $tests]);
    }

    public function createTest()
    {
        if (strtolower($this->request->getMethod()) === 'post') {
            $testModel = new TestModel();
            
            $title = trim($this->request->getPost('title') ?? '');
            if (empty($title)) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Test title is required']);
                }
                return redirect()->back()->with('error', 'Test title is required');
            }

            // Generate unique slug
            $baseSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
            if (empty($baseSlug)) $baseSlug = 'aptitude-test';
            $slug = $baseSlug;
            $counter = 1;
            while ($testModel->where('slug', $slug)->first()) {
                $slug = $baseSlug . '-' . $counter++;
            }

            $testId = $testModel->insert([
                'category_id' => $this->request->getPost('category_id') ?: 1,
                'title' => $title,
                'slug' => $slug,
                'description' => $this->request->getPost('description') ?? '',
                'duration_mins' => (int) ($this->request->getPost('duration_mins') ?: 20),
                'num_questions' => (int) ($this->request->getPost('num_questions') ?: 10),
                'pass_threshold' => (int) ($this->request->getPost('pass_threshold') ?: 50),
                'difficulty' => $this->request->getPost('difficulty') ?: 'medium',
                'is_active' => 1
            ]);

            if (!$testId) {
                $errors = implode(', ', $testModel->errors() ?: ['Failed to save test parameters']);
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON(['success' => false, 'message' => $errors]);
                }
                return redirect()->back()->with('error', $errors);
            }

            // Save questions if provided in request
            $questionsRaw = $this->request->getPost('questions');
            if ($questionsRaw) {
                $this->saveQuestionsForTest((int) $testId, $questionsRaw);
            }

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'test_id' => $testId,
                    'message' => 'Aptitude Test created successfully!'
                ]);
            }

            return redirect()->to('/admin/aptitude')->with('success', 'Aptitude Test created successfully.');
        }

        return view('admin/aptitude/create');
    }

    public function editTest($id)
    {
        $testModel = new TestModel();
        $test = $testModel->find($id);
        if (!$test) {
            return redirect()->to('/admin/aptitude')->with('error', 'Test not found');
        }

        $db = \Config\Database::connect();
        $questions = $db->table('questions')
            ->where('test_id', $id)
            ->get()
            ->getResultArray();

        foreach ($questions as &$q) {
            $options = $db->table('question_options')
                ->where('question_id', $q['id'])
                ->get()
                ->getResultArray();
            $q['options'] = $options;
        }

        return view('admin/aptitude/create', [
            'test' => $test,
            'questions' => $questions
        ]);
    }

    public function updateTest($id)
    {
        $testModel = new TestModel();
        $test = $testModel->find($id);
        if (!$test) {
            if ($this->request->isAJAX()) return $this->response->setJSON(['success' => false, 'message' => 'Test not found']);
            return redirect()->to('/admin/aptitude')->with('error', 'Test not found');
        }

        $title = trim($this->request->getPost('title') ?? '');
        $data = [
            'category_id' => $this->request->getPost('category_id') ?: 1,
            'title' => $title ?: (is_array($test) ? $test['title'] : $test->title),
            'description' => $this->request->getPost('description') ?? '',
            'duration_mins' => (int) ($this->request->getPost('duration_mins') ?: 20),
            'num_questions' => (int) ($this->request->getPost('num_questions') ?: 10),
            'pass_threshold' => (int) ($this->request->getPost('pass_threshold') ?: 50),
            'difficulty' => $this->request->getPost('difficulty') ?: 'medium'
        ];

        $testModel->update($id, $data);

        $questionsRaw = $this->request->getPost('questions');
        if ($questionsRaw) {
            $this->saveQuestionsForTest((int) $id, $questionsRaw);
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true, 'message' => 'Test updated successfully']);
        }

        return redirect()->to('/admin/aptitude')->with('success', 'Test updated successfully');
    }

    public function deleteTest($id)
    {
        $testModel = new TestModel();
        $testModel->delete($id);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true, 'message' => 'Test deleted successfully']);
        }
        return redirect()->to('/admin/aptitude')->with('success', 'Test deleted successfully');
    }

    public function questions($testId)
    {
        $db = \Config\Database::connect();
        $questions = $db->table('questions')
            ->where('test_id', $testId)
            ->get()
            ->getResultArray();

        foreach ($questions as &$q) {
            $options = $db->table('question_options')
                ->where('question_id', $q['id'])
                ->get()
                ->getResultArray();
            $q['options'] = $options;
        }

        return $this->response->setJSON(['success' => true, 'questions' => $questions]);
    }

    public function saveQuestions($testId)
    {
        $questionsRaw = $this->request->getPost('questions');
        $this->saveQuestionsForTest((int) $testId, $questionsRaw);

        return $this->response->setJSON(['success' => true, 'message' => 'Questions saved successfully']);
    }

    public function aiGenerateQuestions()
    {
        $title = $this->request->getPost('title') ?? 'General Aptitude';
        $description = $this->request->getPost('description') ?? '';
        $numQuestions = (int) ($this->request->getPost('num_questions') ?: 5);

        $aiService = new AiService();
        $questions = $aiService->generateCustomAptitudeQuestions($title, $description, $numQuestions);

        if (empty($questions)) {
            $questions = $aiService->getFallbackCourseTestQuestions($title, $numQuestions);
        }

        return $this->response->setJSON(['success' => true, 'questions' => $questions]);
    }

    private function saveQuestionsForTest(int $testId, $questionsRaw): void
    {
        $decoded = is_string($questionsRaw) ? json_decode($questionsRaw, true) : $questionsRaw;
        if (!is_array($decoded) || empty($decoded)) return;

        $questionModel = new QuestionModel();
        $optionModel = new QuestionOptionModel();

        // Delete existing questions for this test to perform fresh sync
        $existingQs = $questionModel->where('test_id', $testId)->findAll();
        foreach ($existingQs as $eq) {
            $qId = is_array($eq) ? $eq['id'] : $eq->id;
            $optionModel->where('question_id', $qId)->delete();
            $questionModel->delete($qId);
        }

        $savedCount = 0;
        foreach ($decoded as $q) {
            $body = trim($q['question'] ?? $q['body'] ?? '');
            if (empty($body)) continue;

            $qId = $questionModel->insert([
                'test_id' => $testId,
                'type' => 'mcq',
                'body' => $body,
                'difficulty' => 'intermediate',
                'explanation' => $q['explanation'] ?? '',
                'points' => 1,
                'is_active' => 1
            ]);

            $options = $q['options'] ?? [];
            foreach ($options as $idx => $opt) {
                $optText = is_array($opt) ? ($opt['text'] ?? $opt['body'] ?? '') : (string) $opt;
                $isCorrect = is_array($opt) ? (!empty($opt['is_correct']) ? 1 : 0) : 0;
                if (empty($optText)) continue;

                $optionModel->insert([
                    'question_id' => $qId,
                    'body' => $optText,
                    'is_correct' => $isCorrect,
                    'sort_order' => $idx
                ]);
            }
            $savedCount++;
        }

        // Update num_questions on test record
        $testModel = new TestModel();
        $testModel->update($testId, ['num_questions' => max(1, $savedCount)]);
    }

    public function importQuestions($testId)
    {
        if (strtolower($this->request->getMethod()) === 'post') {
            $file = $this->request->getFile('csv_file');

            if (!$file || !$file->isValid()) {
                return redirect()->back()->with('error', 'Invalid file upload.');
            }

            if (($handle = fopen($file->getTempName(), 'r')) !== false) {
                $questionModel = new QuestionModel();
                $optionModel = new QuestionOptionModel();
                
                fgetcsv($handle);
                
                while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                    if (count($data) < 9) continue;
                    
                    $qId = $questionModel->insert([
                        'test_id' => $testId,
                        'type' => trim($data[0]),
                        'body' => trim($data[1]),
                        'difficulty' => trim($data[7]),
                        'explanation' => trim($data[8]),
                        'points' => 1
                    ]);

                    $options = [
                        'A' => trim($data[2]),
                        'B' => trim($data[3]),
                        'C' => trim($data[4]),
                        'D' => trim($data[5])
                    ];
                    
                    $correctLetter = strtoupper(trim($data[6]));

                    foreach ($options as $letter => $body) {
                        if (!empty($body)) {
                            $optionModel->insert([
                                'question_id' => $qId,
                                'body' => $body,
                                'is_correct' => ($letter === $correctLetter) ? 1 : 0
                            ]);
                        }
                    }
                }
                fclose($handle);
                return redirect()->to("/admin/aptitude")->with('success', 'Questions imported successfully.');
            }
        }
        
        return view('admin/aptitude/import', ['test_id' => $testId]);
    }
}
