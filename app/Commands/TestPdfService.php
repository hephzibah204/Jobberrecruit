<?php 
namespace App\Commands; 
use CodeIgniter\CLI\BaseCommand; 
use CodeIgniter\CLI\CLI; 
class TestPdfService extends BaseCommand { 
    protected $group = "Dev"; 
    protected $name = "test:pdf"; 
    public function run(array $params) { 
        try { 
            \App\Services\PdfService::generateFromHtml("<h1>Hello World</h1>", WRITEPATH."testpdf.pdf", "landscape"); 
            CLI::write("Success", "green"); 
        } catch(\Throwable $e) { 
            CLI::error("Error: " . $e->getMessage()); 
        } 
    } 
}
