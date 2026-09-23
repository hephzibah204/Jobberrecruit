<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Services\AiService;

class InterviewTranscription extends Controller
{
    /**
     * Process an audio upload and return its transcription via AI
     */
    public function process()
    {
        $file = $this->request->getFile('audio');

        if (!$file || !$file->isValid()) {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => 'Valid audio file is required'
            ]);
        }

        // Get the file contents and encode it as base64
        $fileData = file_get_contents($file->getTempName());
        $base64Data = base64_encode($fileData);
        $mimeType = $file->getMimeType();

        // Let the AI Service handle the transcription via Gemini Flash
        $aiService = new AiService();
        $transcript = $aiService->transcribeAudio($base64Data, $mimeType);

        if ($transcript === null) {
            return $this->response->setStatusCode(500)->setJSON([
                'error' => 'Transcription failed. Please try again.'
            ]);
        }

        return $this->response->setJSON([
            'transcript' => $transcript
        ]);
    }
}
