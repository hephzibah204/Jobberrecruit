<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Models\JobSeekerModel;

/**
 * Test candidate profile visibility behavior across search and discovery.
 *
 * @internal
 */
final class CandidateVisibilityTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $namespace = null;
    protected $refresh   = true;
    protected $seed      = '';

    private JobSeekerModel $seekerModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seekerModel = new JobSeekerModel();
    }

    private function createCandidate(array $attrs = []): array
    {
        $db = \Config\Database::connect();

        $db->table('users')->insert([
            'username'   => 'seeker_' . uniqid(),
            'user_type'  => 'job_seeker',
            'status'     => 'active',
            'active'     => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $userId = (int) $db->insertID();

        $defaultAttrs = [
            'user_id'            => $userId,
            'full_name'          => 'John Doe',
            'job_title'          => 'Software Engineer',
            'skills'             => 'PHP, MySQL, JavaScript',
            'location'           => 'Lagos',
            'phone'              => '08012345678',
            'is_visible'         => 1,
            'profile_completion' => 80,
            'created_at'         => date('Y-m-d H:i:s'),
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        $data = array_merge($defaultAttrs, $attrs);
        $db->table('job_seekers')->insert($data);
        $seekerId = (int) $db->insertID();

        return array_merge($data, ['id' => $seekerId]);
    }

    public function testCandidateAppearsInEmployerSearchWhenVisibilityIsOn(): void
    {
        $cand = $this->createCandidate(['is_visible' => 1, 'full_name' => 'Visible Developer']);

        $results = $this->seekerModel->getCandidates([], 20, false);
        $ids = array_map(static fn ($r) => (int) $r->id, is_array($results) ? $results : iterator_to_array($results));

        $this->assertContains($cand['id'], $ids, 'Candidate with is_visible=1 must appear in employer candidate search');
    }

    public function testCandidateDoesNotAppearInEmployerSearchWhenVisibilityIsOff(): void
    {
        $cand = $this->createCandidate(['is_visible' => 0, 'full_name' => 'Hidden Developer']);

        $results = $this->seekerModel->getCandidates([], 20, false);
        $ids = array_map(static fn ($r) => (int) $r->id, is_array($results) ? $results : iterator_to_array($results));

        $this->assertNotContains($cand['id'], $ids, 'Candidate with is_visible=0 must NOT appear in employer candidate search');
    }

    public function testHiddenCandidateNotSearchableByKeyword(): void
    {
        $cand = $this->createCandidate(['is_visible' => 0, 'full_name' => 'UniqueNameSearchTarget']);

        $results = $this->seekerModel->getCandidates(['keyword' => 'UniqueNameSearchTarget'], 20, false);
        $ids = array_map(static fn ($r) => (int) $r->id, is_array($results) ? $results : iterator_to_array($results));

        $this->assertNotContains($cand['id'], $ids, 'Hidden candidate must not appear even when their exact name is searched');
    }

    public function testHiddenCandidateNotSearchableBySkill(): void
    {
        $cand = $this->createCandidate(['is_visible' => 0, 'skills' => 'UniqueSuperSkillXYZ']);

        $results = $this->seekerModel->getCandidates(['skill' => ['UniqueSuperSkillXYZ']], 20, false);
        $ids = array_map(static fn ($r) => (int) $r->id, is_array($results) ? $results : iterator_to_array($results));

        $this->assertNotContains($cand['id'], $ids, 'Hidden candidate must not appear when their skill is filtered');
    }

    public function testHiddenCandidateExcludedFromSidebarAggregates(): void
    {
        $this->createCandidate(['is_visible' => 0, 'job_title' => 'StealthRole999']);

        $counts = $this->seekerModel->countByJobTitle();
        $titles = array_map(static fn ($r) => $r->job_title, $counts);

        $this->assertNotContains('StealthRole999', $titles, 'Hidden candidates must not be counted in sidebar aggregations');
    }

    public function testTogglingVisibilityUpdatesSearchability(): void
    {
        $cand = $this->createCandidate(['is_visible' => 1, 'full_name' => 'Toggle Candidate']);

        // Initially visible
        $results = $this->seekerModel->getCandidates([], 20, false);
        $ids = array_map(static fn ($r) => (int) $r->id, is_array($results) ? $results : iterator_to_array($results));
        $this->assertContains($cand['id'], $ids);

        // Turn OFF
        $this->seekerModel->update($cand['id'], ['is_visible' => 0]);
        $resultsOff = $this->seekerModel->getCandidates([], 20, false);
        $idsOff = array_map(static fn ($r) => (int) $r->id, is_array($resultsOff) ? $resultsOff : iterator_to_array($resultsOff));
        $this->assertNotContains($cand['id'], $idsOff, 'Candidate must disappear from search when visibility is switched OFF');

        // Turn ON
        $this->seekerModel->update($cand['id'], ['is_visible' => 1]);
        $resultsOn = $this->seekerModel->getCandidates([], 20, false);
        $idsOn = array_map(static fn ($r) => (int) $r->id, is_array($resultsOn) ? $resultsOn : iterator_to_array($resultsOn));
        $this->assertContains($cand['id'], $idsOn, 'Candidate must reappear in search when visibility is switched ON');
    }

    public function testVisibleCandidateWithoutOptionalFieldsIsSearchable(): void
    {
        // Candidate has visibility ON but optional fields (phone, portfolio, certs) are empty
        $cand = $this->createCandidate([
            'is_visible'         => 1,
            'full_name'          => 'Minimalist Seeker',
            'phone'              => null,
            'portfolio'          => null,
            'experience_years'   => null,
            'desired_salary'     => null,
            'profile_completion' => 30, // low completion because optional fields are omitted
        ]);

        $results = $this->seekerModel->getCandidates([], 20, false);
        $ids = array_map(static fn ($r) => (int) $r->id, is_array($results) ? $results : iterator_to_array($results));

        $this->assertContains(
            $cand['id'],
            $ids,
            'A candidate with is_visible=1 must not be blocked from search results just because optional fields are empty'
        );
    }

    public function testCoreProfileReaches100PercentCompletionWithoutOptionalBonusFields(): void
    {
        $db = \Config\Database::connect();
        $seeker = $this->createCandidate([
            'full_name'       => 'Complete Candidate',
            'phone'           => '08011223344',
            'dob'             => '1995-05-15',
            'gender'          => 'female',
            'location'        => 'Lagos',
            'job_title'       => 'Software Engineer',
            'employment_type' => 'Full Time',
            'desired_salary'  => 500000,
            'salary_type'     => 'monthly',
            'bio'             => 'Experienced software engineer specializing in web application development.',
            'skills'          => 'PHP, MySQL, JavaScript, React, Git',
            'resume'          => 'uploads/candidates/1/resume.pdf',
            'portfolio'       => null, // OPTIONAL - empty
            'languages'       => null, // OPTIONAL - empty
        ]);

        // Insert structured experience
        $db->table('job_seeker_experiences')->insert([
            'job_seeker_id' => $seeker['id'],
            'job_title'     => 'Lead Developer',
            'company'       => 'Tech Corp',
            'start_date'    => '2020-01-01',
            'is_current'    => 1,
        ]);

        // Insert structured education
        $db->table('job_seeker_education')->insert([
            'job_seeker_id'  => $seeker['id'],
            'degree'         => 'B.Sc.',
            'field_of_study' => 'Computer Science',
            'school'         => 'University of Lagos',
            'start_year'     => '2014',
            'end_year'       => '2018',
        ]);

        // Insert category/industry
        $db->table('job_seeker_industries')->insert([
            'job_seeker_id' => $seeker['id'],
            'industry_id'   => 1,
        ]);

        $seekerEntity = $this->seekerModel->find($seeker['id']);
        $completion = $seekerEntity->getProfileCompletion();

        $this->assertSame(
            100,
            $completion,
            'Candidate with all core sections completed must achieve 100% completion without requiring optional portfolio or external certifications'
        );
    }
}
