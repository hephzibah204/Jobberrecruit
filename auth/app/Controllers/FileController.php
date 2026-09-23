<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Files\File;

class FileController extends Controller
{
    public function serve($type, $userId, $filename)
    {
        $auth = service('auth');
        if (!$auth->loggedIn()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $type = basename($type);
        $userId = (int) $userId;
        $filename = basename($filename);

        if ($userId <= 0 || empty($filename)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $path = WRITEPATH . "uploads/{$type}/{$userId}/{$filename}";
        $realPath = realpath($path);
        $realBase = realpath(WRITEPATH . 'uploads');

        if ($realPath === false || $realBase === false || strpos($realPath, $realBase) !== 0) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if (!is_file($path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $file = new File($path);
        return $this->response->download($path, null)->setFileName($file->getBasename());
    }
}
