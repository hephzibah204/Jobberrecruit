<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\AiImageModel;

class ProxyImages extends BaseCommand
{
    protected $group       = 'AI';
    protected $name        = 'images:proxy';
    protected $description = 'Process pending AI image proxy queue. Downloads, validates, and stores external images locally.';

    public function run(array $params)
    {
        $limit = isset($params[0]) ? (int) $params[0] : 20;
        $model = new AiImageModel();
        $pending = $model->findPending($limit);

        if (empty($pending)) {
            CLI::write('No pending images to proxy.', 'yellow');
            return;
        }

        CLI::write('Processing ' . count($pending) . ' pending image(s)...', 'cyan');

        $success = 0;
        $failed = 0;

        foreach ($pending as $row) {
            CLI::write('  Proxying: ' . $row->origin_url, 'blue');
            $result = $this->processImage($row, $model);

            if (isset($result['url'])) {
                CLI::write('    -> OK: ' . $result['url'], 'green');
                $success++;
            } else {
                CLI::write('    -> FAILED: ' . ($result['error'] ?? 'Unknown error'), 'red');
                $failed++;
            }
        }

        CLI::write("Done. {$success} succeeded, {$failed} failed.", $failed > 0 ? 'yellow' : 'green');
    }

    /**
     * @return array{url?: string, error?: string}
     */
    private function processImage(object $row, AiImageModel $model): array
    {
        $origin = $row->origin_url;

        $ch = curl_init($origin);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        $data = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err || !$data) {
            $msg = 'Failed to download image: ' . ($err ?: 'no data');
            $model->markFailed($row->id, $msg);
            return ['error' => $msg];
        }

        $size = strlen($data);
        if ($size > 2 * 1024 * 1024) {
            $msg = 'Image exceeds maximum allowed size of 2MB';
            $model->markFailed($row->id, $msg);
            return ['error' => $msg];
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->buffer($data);
        $allowed = ['image/jpeg','image/png','image/webp'];
        if (!in_array($mime, $allowed, true)) {
            $msg = 'Unsupported image MIME type: ' . $mime;
            $model->markFailed($row->id, $msg);
            return ['error' => $msg];
        }

        $checksum = hash('sha256', $data);

        // Check for duplicate by checksum
        $dup = $model->where('checksum', $checksum)->where('status', 'completed')->first();
        if ($dup && $dup->proxied_path && $dup->id !== $row->id) {
            $model->markCompleted($row->id, $dup->proxied_path, $checksum, $mime, $size);
            return ['url' => base_url($dup->proxied_path)];
        }

        $ext = $mime === 'image/png' ? 'png' : ($mime === 'image/webp' ? 'webp' : 'jpg');
        $dir = ROOTPATH . 'public/uploads/ai-images/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $filename = $checksum . '.' . $ext;
        $path = $dir . $filename;
        file_put_contents($path, $data);
        $publicPath = 'uploads/ai-images/' . $filename;

        $model->markCompleted($row->id, $publicPath, $checksum, $mime, $size);

        return ['url' => base_url($publicPath)];
    }
}
