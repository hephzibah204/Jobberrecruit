<?php

namespace App\Services;

use Spatie\Browsershot\Browsershot;

class PdfService
{
    /**
     * Generate a PDF from HTML using Gotenberg (Chromium) as primary,
     * and Browsershot as an alternative fallback.
     *
     * @param string $html The HTML content
     * @param string $pdfPath The absolute path where the PDF should be saved
     * @param string $orientation 'portrait' or 'landscape'
     * @return bool True on success
     * @throws \Exception If generation fails on both
     */
    public static function generateFromHtml(string $html, string $pdfPath, string $orientation = 'portrait')
    {
        try {
            return self::generateWithGotenberg($html, $pdfPath, $orientation);
        } catch (\Throwable $e) {
            log_message('warning', 'Gotenberg PDF generation failed, falling back to Browsershot: ' . $e->getMessage());
            
            try {
                return self::generateWithBrowsershot($html, $pdfPath, $orientation);
            } catch (\Throwable $e2) {
                log_message('error', 'Both Gotenberg and Browsershot PDF generation failed. Browsershot error: ' . $e2->getMessage());
                throw new \Exception('Gotenberg Error: ' . $e->getMessage() . ' | Browsershot Error: ' . $e2->getMessage());
            }
        }
    }

    private static function generateWithGotenberg(string $html, string $pdfPath, string $orientation)
    {
        $gotenbergUrl = env('gotenberg_url') ?: env('GOTENBERG_URL');
        if (empty($gotenbergUrl)) {
            $gotenbergUrl = 'http://localhost:3000';
        }

        $tempHtmlPath = $pdfPath . '.html';
        file_put_contents($tempHtmlPath, $html);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, rtrim($gotenbergUrl, '/') . '/forms/chromium/convert/html');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

        $postFields = [
            'files' => new \CURLFile($tempHtmlPath, 'text/html', 'index.html'),
            'marginTop' => 0,
            'marginBottom' => 0,
            'marginLeft' => 0,
            'marginRight' => 0,
            'printBackground' => 1
        ];

        if ($orientation === 'landscape') {
            $postFields['paperWidth'] = 11.69;
            $postFields['paperHeight'] = 8.27;
            $postFields['landscape'] = 1;
        } else {
            $postFields['paperWidth'] = 8.27;
            $postFields['paperHeight'] = 11.69;
        }

        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            @unlink($tempHtmlPath);
            throw new \Exception('Gotenberg curl error: ' . $error);
        }
        
        curl_close($ch);
        @unlink($tempHtmlPath);

        if ($httpCode !== 200) {
            throw new \Exception('Gotenberg returned HTTP ' . $httpCode . ': ' . $result);
        }

        file_put_contents($pdfPath, $result);
        return true;
    }

    private static function generateWithBrowsershot(string $html, string $pdfPath, string $orientation)
    {
        $browsershot = Browsershot::html($html)
            ->format('A4')
            ->margins(0, 0, 0, 0)
            ->showBackground()
            ->noSandbox();

        if ($orientation === 'landscape') {
            $browsershot->landscape()
                ->windowSize(1056, 748)
                ->deviceScaleFactor(2)
                ->emulateMedia('screen');
        }

        $envNode = env('node_binary_path') ?: env('NODE_BINARY_PATH');
        $envNpm  = env('npm_binary_path')  ?: env('NPM_BINARY_PATH');

        $linuxNodePaths = [
            '/usr/bin/node', '/usr/local/bin/node', '/opt/cpanel/ea-nodejs18/bin/node',
            '/opt/cpanel/ea-nodejs20/bin/node', '/opt/alt/alt-nodejs18/root/usr/bin/node',
            '/opt/alt/alt-nodejs20/root/usr/bin/node', '/opt/alt/alt-nodejs22/root/usr/bin/node',
            '/home/jobbcfsf/bin/node', '/home/jobbcfsf/.nvm/versions/node/current/bin/node',
        ];
        $linuxNpmPaths = [
            '/usr/bin/npm', '/usr/local/bin/npm', '/opt/cpanel/ea-nodejs18/bin/npm',
            '/opt/cpanel/ea-nodejs20/bin/npm', '/opt/alt/alt-nodejs18/root/usr/bin/npm',
            '/opt/alt/alt-nodejs20/root/usr/bin/npm', '/home/jobbcfsf/bin/npm',
            '/home/jobbcfsf/.nvm/versions/node/current/bin/npm',
        ];

        if ($envNode && file_exists($envNode)) {
            $browsershot->setNodeBinary($envNode);
        } elseif (DIRECTORY_SEPARATOR === '\\' && file_exists('C:\\Program Files\\nodejs\\node.exe')) {
            $browsershot->setNodeBinary('C:\\Program Files\\nodejs\\node.exe');
        } else {
            foreach ($linuxNodePaths as $p) { if (file_exists($p)) { $browsershot->setNodeBinary($p); break; } }
        }

        if ($envNpm && file_exists($envNpm)) {
            $browsershot->setNpmBinary($envNpm);
        } elseif (DIRECTORY_SEPARATOR === '\\' && file_exists('C:\\Program Files\\nodejs\\npm.cmd')) {
            $browsershot->setNpmBinary('C:\\Program Files\\nodejs\\npm.cmd');
        } else {
            foreach ($linuxNpmPaths as $p) { if (file_exists($p)) { $browsershot->setNpmBinary($p); break; } }
        }

        $browsershot->save($pdfPath);
        return true;
    }
}
