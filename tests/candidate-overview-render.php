<?php
namespace CodeIgniter { class Model {} }
namespace {
    define('FCPATH',dirname(__DIR__).DIRECTORY_SEPARATOR);
    require FCPATH.'app/Models/AptitudeTestInvitationModel.php';
    function base_url($path=''){ return 'http://dashboard.test/'.ltrim($path,'/'); }
    function esc($value,$context='html'){ return htmlspecialchars((string)$value,ENT_QUOTES,'UTF-8'); }
    function get_site_setting($name,$default=null){return $default;}
    function resolve_image_url(...$args){return base_url('images/favicon.png');}
    function format_job_url($job){return base_url('jobs/'.$job->id);}
    function model(...$args){throw new \RuntimeException('Dashboard view must not query the database');}
    class OverviewPreview {
        public array $sections=[];
        private string $section='';
        function extend($name){}
        function section($name){$this->section=$name;ob_start();}
        function endSection(){$this->sections[$this->section]=ob_get_clean();}
        function render(int $match){
            $candidate=(object)['id'=>1,'full_name'=>'   ','resume'=>null];
            $totalApplications=2;$savedJobs=1;$jobsViewed=1;$profileCompletion=10;$profileChecklist=[];
            $weeklyChartData=[0,1,0,0,0,0,0];
            $skillCategories=[(object)['name'=>'PHP','match'=>$match]];
            $recommendedJobs=[(object)['id'=>9,'title'=>'Recommended role','created_at'=>date('Y-m-d'),'employer_name'=>'Example company','company_logo'=>'','location'=>'Lagos','match_score'=>10]];
            $recentApplications=array_map(fn($status)=>(object)['id'=>1,'job_title'=>'Example role','created_at'=>date('Y-m-d'),'status'=>$status],['shortlisted','reviewed']);
            $aptitudeInvitations=[(object)['id'=>1,'status'=>'pending','test_title'=>str_repeat('LongAssessmentTitle',8),'company_name'=>str_repeat('LongCompany',8),'job_title'=>'Sales Manager','due_date'=>date('Y-m-d H:i:s',time()+86400),'invitation_code'=>'audit']];
            require FCPATH.'app/Views/candidate/dashboard.php';
            echo '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><style>'.file_get_contents(FCPATH.'css/employer-shell.css').'</style>'.$this->sections['styles'].'</head><body><div class="emp-shell"><aside class="emp-sidebar"></aside><div class="emp-main"><main class="emp-content" id="main-content">'.$this->sections['content'].'</main></div></div></body></html>';
        }
    }
    (new OverviewPreview())->render((int)($argv[1]??10));
}
