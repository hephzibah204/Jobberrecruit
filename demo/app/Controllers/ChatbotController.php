<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\AiService;

class ChatbotController extends BaseController
{
    protected $aiService;

    public function __construct()
    {
        $this->aiService = new AiService();
    }

    /**
     * Handle incoming chat messages
     */
    public function sendMessage()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false]);
        }

        $message = $this->request->getPost('message');
        if (empty($message)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Empty message']);
        }

        // Get session chat history
        $session = session();
        $history = $session->get('chat_history') ?? [];

        // Determine context based on user type and current page
        $user = auth()->user();
        $userType = $user ? $user->user_type : 'guest';
        
        $context = "The user is a " . $userType . " on the JobberRecruit platform. ";
        $context .= "Key pages: /jobs (Browse), /register, /login, /employer/dashboard, /candidate/dashboard. ";
        $context .= "Referral program is active: refer friends to earn rewards. ";
        $context .= "AI Resume builder is available for candidates. ";
        
        if ($user) {
            $context .= "User email: " . $user->email . ". ";
        }

        // Get AI response
        $response = $this->aiService->getChatResponse($message, $history, $context);

        // Update history
        $history[] = ['sender' => 'user', 'message' => $message];
        $history[] = ['sender' => 'bot', 'message' => $response];
        
        // Keep only last 10 messages for context efficiency
        if (count($history) > 20) {
            $history = array_slice($history, -20);
        }
        
        $session->set('chat_history', $history);

        return $this->response->setJSON([
            'success' => true,
            'response' => $response
        ]);
    }

    /**
     * Clear chat history
     */
    public function clearHistory()
    {
        session()->remove('chat_history');
        return $this->response->setJSON(['success' => true]);
    }
}
