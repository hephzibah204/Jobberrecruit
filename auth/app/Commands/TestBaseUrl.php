<?php
namespace App\Commands;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestBaseUrl extends BaseCommand {
    protected $group = 'Custom';
    protected $name = 'test:baseurl';
    public function run(array $params) {
        helper('url');
        CLI::write(base_url('https://res.cloudinary.com/demo/image/upload/v1/sample.jpg'));
    }
}
