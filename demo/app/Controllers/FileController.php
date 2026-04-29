<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Files\File;

class FileController extends Controller
{
    public function serve($type, $userId, $filename)
    {
        $path = WRITEPATH . "uploads/{$type}/{$userId}/{$filename}";

        if (!is_file($path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $file = new File($path);
        return $this->response->download($path, null)->setFileName($file->getBasename());
    }
}
