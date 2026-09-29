<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Seeds the aptitude test catalog (skill-practice + role assessments) and a
 * starter question bank. Idempotent: re-running refreshes each managed test
 * and its questions by slug, but SKIPS any test that already has attempts
 * (so it never breaks referential integrity on a live system).
 *
 * Run:  php spark db:seed AptitudeCatalogSeeder
 */
class AptitudeCatalogSeeder extends Seeder
{
    public function run()
    {
        $questionBank = require __DIR__ . '/Data/AptitudeQuestions.php';

        // slug => [category_id, title, description, difficulty, duration_mins, pass_threshold]
        // category_id references job_categories.id
        $catalog = [
            // ── Skill-practice categories ──
            'numerical'         => [2,  'Numerical Reasoning',      'Ratios, data tables, percentages and everyday business maths.',                 'intermediate', 15, 60],
            'verbal'            => [14, 'Verbal Reasoning',         'Comprehension, vocabulary and true/false/cannot-say judgement.',                'intermediate', 15, 60],
            'logical'           => [1,  'Logical Reasoning',        'Number and letter sequences, syllogisms and deductions.',                       'intermediate', 15, 60],
            'abstract'          => [17, 'Abstract Reasoning',       'Patterns, matrices and spatial logic with no words or numbers.',                'beginner',     15, 60],
            'ict'               => [1,  'ICT & Digital Skills',     'Computing basics, office tools, spreadsheets and online safety.',               'intermediate', 15, 60],
            'general-aptitude'  => [8,  'General Aptitude',         'Mixed numerical, verbal and logical items — the classic screener.',             'intermediate', 15, 60],

            // ── Role-based assessments ──
            'software-developer'      => [1, 'Software Developer Aptitude', 'Logic, algorithms, data structures and problem-solving for developer roles.', 'intermediate', 25, 70],
            'data-analysis'           => [1, 'Data Analysis & SQL',         'Spreadsheet logic, SQL queries and data interpretation for analysts.',        'intermediate', 20, 65],
            'accounting-fundamentals' => [2, 'Accounting Fundamentals',     'Core accounting principles, financial statements and bookkeeping basics.',    'beginner',     20, 60],
            'financial-reasoning'     => [2, 'Financial Reasoning',         'Quantitative reasoning, ratios and financial decision-making scenarios.',     'advanced',     25, 65],
            'digital-marketing'       => [3, 'Digital Marketing',           'SEO, social media, content strategy and campaign analytics fundamentals.',    'beginner',     20, 60],
            'office-admin'            => [6, 'Office & Admin Skills',       'Communication, organisation, MS Office and workplace scenario judgement.',    'beginner',     18, 60],
            'engineering-aptitude'    => [4, 'Engineering Aptitude',        'Basic mechanics, electrical principles, and engineering problem-solving.',    'intermediate', 20, 65],
            'customer-service-aptitude' => [5, 'Customer Service Aptitude', 'Communication, problem resolution, empathy, and support metrics.',            'beginner',     15, 60],
            'healthcare-medical-aptitude' => [7, 'Healthcare & Medical Aptitude', 'Anatomy basics, clinical terms, and patient care scenarios.',           'intermediate', 20, 60],
            'construction-real-estate-aptitude' => [9, 'Construction & Real Estate Aptitude', 'Building structure, safety, property terms, and concrete workability.', 'intermediate', 20, 60],
            'hospitality-tourism-aptitude' => [10, 'Hospitality & Tourism Aptitude', 'Hotel operations, customer relations, and business terminology.',     'beginner',     15, 60],
            'legal-aptitude'          => [11, 'Legal Aptitude',             'Legal logic, terminology, civil law standards, and case law principles.',     'intermediate', 20, 65],
            'manufacturing-production-aptitude' => [12, 'Manufacturing & Production Aptitude', 'Lean manufacturing, OEE metrics, quality control, and inventory.', 'intermediate', 20, 60],
            'logistics-transportation-aptitude' => [13, 'Logistics & Transportation Aptitude', 'Freight documentation, reverse logistics, and supply chain modes.', 'intermediate', 18, 60],
            'agriculture-farming-aptitude' => [15, 'Agriculture & Farming Aptitude', 'Crop nutrients, irrigation, hydroponics, and modern farming.',       'beginner',     15, 60],
            'security-safety-aptitude' => [16, 'Security & Safety Aptitude',   'Fire safety, emergency protocols, PPE, and occupational security.',           'beginner',     15, 60],
            'science-research-aptitude' => [18, 'Science & Research Aptitude',   'Scientific method, chemical bonds, and research statistics.',                'intermediate', 20, 60],
            'procurement-supply-chain-aptitude' => [19, 'Procurement & Supply Chain Aptitude', 'Sourcing metrics, bullwhip effect, supplier scorecards, and RFQs.', 'intermediate', 20, 60],
            'ngo-non-profit-aptitude' => [20, 'NGO & Non-Profit Aptitude',  'Grant sourcing, M&E metrics, non-profit governance, and bylaws.',             'intermediate', 18, 60],
        ];

        $db  = $this->db;
        $now = date('Y-m-d H:i:s');
        $seeded = 0;
        $skipped = 0;

        foreach ($catalog as $slug => $meta) {
            [$categoryId, $title, $desc, $difficulty, $duration, $pass] = $meta;
            $questions = $questionBank[$slug] ?? [];
            if (empty($questions)) {
                continue;
            }

            // num_questions = min(10, available) so the test is always runnable
            $numQuestions = min(10, count($questions));

            $existing = $db->table('tests')->where('slug', $slug)->get()->getRowArray();

            if ($existing) {
                // Never touch a test that already has attempts (protects live data)
                $hasAttempts = $db->table('test_attempts')->where('test_id', $existing['id'])->countAllResults();
                if ($hasAttempts > 0) {
                    $skipped++;
                    continue;
                }
                // Clean refresh: drop old questions/options for this test, then the test
                $qIds = array_column(
                    $db->table('questions')->select('id')->where('test_id', $existing['id'])->get()->getResultArray(),
                    'id'
                );
                if ($qIds) {
                    $db->table('question_options')->whereIn('question_id', $qIds)->delete();
                    $db->table('questions')->where('test_id', $existing['id'])->delete();
                }
                $db->table('tests')->where('id', $existing['id'])->delete();
            }

            $db->table('tests')->insert([
                'category_id'    => $categoryId,
                'title'          => $title,
                'slug'           => $slug,
                'description'    => $desc,
                'duration_mins'  => $duration,
                'num_questions'  => $numQuestions,
                'pass_threshold' => $pass,
                'difficulty'     => $difficulty,
                'is_active'      => 1,
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);
            $testId = $db->insertID();

            foreach ($questions as $q) {
                $db->table('questions')->insert([
                    'test_id'     => $testId,
                    'type'        => 'mcq',
                    'body'        => $q['q'],
                    'difficulty'  => $q['d'] ?? $difficulty,
                    'points'      => 1,
                    'explanation' => $q['e'] ?? '',
                    'is_active'   => 1,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);
                $questionId = $db->insertID();

                $sort = 0;
                foreach ($q['o'] as $opt) {
                    $db->table('question_options')->insert([
                        'question_id' => $questionId,
                        'body'        => $opt[0],
                        'is_correct'  => $opt[1] ? 1 : 0,
                        'sort_order'  => $sort++,
                    ]);
                }
            }

            $seeded++;
        }

        echo "AptitudeCatalogSeeder: {$seeded} tests seeded, {$skipped} skipped (had attempts).\n";
    }
}
