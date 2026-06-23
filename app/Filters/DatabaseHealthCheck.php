<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Database;

class DatabaseHealthCheck implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        try {
            $db = Database::connect();
            $db->query('SELECT 1');
        } catch (\Throwable $e) {
            return redirect()->to(base_url('errors/database_down'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after response
    }
}
?>
