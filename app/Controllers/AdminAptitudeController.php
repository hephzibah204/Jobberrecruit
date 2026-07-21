<?php

namespace App\Controllers;

use App\Models\TestModel;
use App\Models\QuestionModel;
use App\Models\QuestionOptionModel;

class AdminAptitudeController extends BaseController
{
    public function index()
    {
        $testModel = new TestModel();
        $tests = $testModel->findAll();
        return view('admin/aptitude/index', ['tests' => $tests]);
    }

    public function createTest()
    {
        if ($this->request->getMethod() === 'post') {
            $testModel = new TestModel();
            
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->request->getPost('title'))));
            
            $testModel->insert([
                'category_id' => $this->request->getPost('category_id'),
                'title' => $this->request->getPost('title'),
                'slug' => $slug,
                'description' => $this->request->getPost('description'),
                'duration_mins' => $this->request->getPost('duration_mins'),
                'num_questions' => $this->request->getPost('num_questions'),
                'pass_threshold' => $this->request->getPost('pass_threshold'),
                'difficulty' => $this->request->getPost('difficulty')
            ]);
            
            return redirect()->to('/admin/aptitude')->with('success', 'Test created successfully.');
        }

        return view('admin/aptitude/create');
    }

    public function importQuestions($testId)
    {
        if ($this->request->getMethod() === 'post') {
            $file = $this->request->getFile('csv_file');

            if (!$file->isValid()) {
                return redirect()->back()->with('error', 'Invalid file upload.');
            }

            if (($handle = fopen($file->getTempName(), 'r')) !== false) {
                $questionModel = new QuestionModel();
                $optionModel = new QuestionOptionModel();
                
                // Skip header row
                fgetcsv($handle);
                
                // Columns: type, body, option_a, option_b, option_c, option_d, correct_letter, difficulty, explanation
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
                return redirect()->to("/admin/aptitude/tests/{$testId}/questions")->with('success', 'Questions imported successfully.');
            }
        }
        
        return view('admin/aptitude/import', ['test_id' => $testId]);
    }
}
