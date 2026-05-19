<?php

namespace App\Controllers;

use App\Models\ConversationModel;
use App\Models\MessageModel;
use App\Models\EmployerModel;
use App\Models\JobSeekerModel;
use App\Models\CandidateUnlockModel;
use App\Services\CreditService;

class MessageController extends BaseController
{
    protected $conversationModel;
    protected $messageModel;

    public function __construct()
    {
        $this->conversationModel = model(ConversationModel::class);
        $this->messageModel = model(MessageModel::class);
    }

    public function inbox()
    {
        $user = auth()->user();
        $unreadCount = $this->messageModel->getUnreadCount($user->id, $user->user_type);

        if ($user->user_type === 'employer') {
            $employerModel = model(EmployerModel::class);
            $employer = $employerModel->where('user_id', $user->id)->first();
            $conversations = $employer ? $this->conversationModel->getConversationsForEmployer($employer->id) : [];
        } else {
            $conversations = $this->conversationModel->getConversationsForSeeker($user->id);
        }

        return view('messages/inbox', [
            'title' => 'Messages',
            'conversations' => $conversations,
            'unreadCount' => $unreadCount,
        ]);
    }

    public function conversation($conversationId)
    {
        $user = auth()->user();
        $conversation = $this->conversationModel->find($conversationId);

        if (!$conversation) {
            return redirect()->to($user->user_type === 'employer' ? 'employer/messages' : 'candidate/messages')
                ->with('error', 'Conversation not found');
        }

        $isParticipant = ($user->user_type === 'employer' && $conversation['employer_id'] == $this->getEmployerId($user->id))
            || ($user->user_type === 'job_seeker' && $conversation['job_seeker_id'] == $this->getSeekerId($user->id));

        if (!$isParticipant) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $messages = $this->messageModel->getConversationMessages($conversationId, $user->id, $user->user_type);

        $otherParty = null;
        if ($user->user_type === 'employer') {
            $seekerModel = model(JobSeekerModel::class);
            $otherParty = $seekerModel->find($conversation['job_seeker_id']);
        } else {
            $employerModel = model(EmployerModel::class);
            $otherParty = $employerModel->find($conversation['employer_id']);
        }

        return view('messages/conversation', [
            'title' => 'Messages',
            'conversation' => $conversation,
            'messages' => $messages,
            'otherParty' => $otherParty,
        ]);
    }

    public function send()
    {
        if (!$this->request->isAJAX()) {
            return $this->fail('Invalid request');
        }

        $user = auth()->user();
        $recipientId = (int) $this->request->getPost('recipient_id');
        $message = trim($this->request->getPost('message'));
        $jobId = (int) ($this->request->getPost('job_id') ?? 0);

        if (empty($message)) {
            return $this->fail('Message cannot be empty');
        }

        if ($user->user_type === 'employer') {
            $employerModel = model(EmployerModel::class);
            $employer = $employerModel->where('user_id', $user->id)->first();
            if (!$employer) {
                return $this->fail('Employer profile not found');
            }

            $unlockModel = model(CandidateUnlockModel::class);
            $hasUnlocked = $unlockModel->where('employer_id', $employer->id)
                ->where('job_seeker_id', $recipientId)
                ->first();

            if (!$hasUnlocked) {
                return $this->fail('You must unlock this candidate\'s contact details first');
            }

            $seekerId = $recipientId;
            $employerId = $employer->id;
        } else {
            $seekerModel = model(JobSeekerModel::class);
            $seeker = $seekerModel->where('user_id', $user->id)->first();
            if (!$seeker) {
                return $this->fail('Candidate profile not found');
            }

            $seekerId = $seeker->id;
            $employerId = $recipientId;
        }

        $conversation = $this->conversationModel
            ->where('employer_id', $employerId)
            ->where('job_seeker_id', $seekerId)
            ->first();

        if (!$conversation) {
            $conversationId = $this->conversationModel->insert([
                'employer_id' => $employerId,
                'job_seeker_id' => $seekerId,
                'job_id' => $jobId > 0 ? $jobId : null,
                'last_message' => $message,
                'last_message_at' => date('Y-m-d H:i:s'),
                'is_active' => 1,
            ]);
        } else {
            $conversationId = $conversation['id'];
            $this->conversationModel->update($conversationId, [
                'last_message' => $message,
                'last_message_at' => date('Y-m-d H:i:s'),
            ]);
        }

        $senderType = $user->user_type === 'employer' ? 'employer' : 'job_seeker';
        $messageId = $this->messageModel->insert([
            'conversation_id' => $conversationId,
            'sender_id' => $user->id,
            'sender_type' => $senderType,
            'message' => $message,
            'is_read' => 0,
        ]);

        return $this->respond([
            'success' => true,
            'message_id' => $messageId,
            'conversation_id' => $conversationId,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function startConversation()
    {
        if (!$this->request->isAJAX()) {
            return $this->fail('Invalid request');
        }

        $user = auth()->user();
        $seekerId = (int) $this->request->getPost('seeker_id');
        $jobId = (int) ($this->request->getPost('job_id') ?? 0);

        if ($user->user_type !== 'employer') {
            return $this->fail('Only employers can start conversations');
        }

        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $user->id)->first();
        if (!$employer) {
            return $this->fail('Employer profile not found');
        }

        $conversation = $this->conversationModel
            ->where('employer_id', $employer->id)
            ->where('job_seeker_id', $seekerId)
            ->first();

        if ($conversation) {
            return $this->respond([
                'success' => true,
                'conversation_id' => $conversation['id'],
                'redirect' => base_url('employer/messages/conversation/' . $conversation['id']),
            ]);
        }

        $conversationId = $this->conversationModel->insert([
            'employer_id' => $employer->id,
            'job_seeker_id' => $seekerId,
            'job_id' => $jobId > 0 ? $jobId : null,
            'is_active' => 1,
        ]);

        return $this->respond([
            'success' => true,
            'conversation_id' => $conversationId,
            'redirect' => base_url('employer/messages/conversation/' . $conversationId),
        ]);
    }

    protected function getEmployerId(int $userId): ?int
    {
        $employerModel = model(EmployerModel::class);
        $employer = $employerModel->where('user_id', $userId)->first();
        return $employer ? $employer->id : null;
    }

    protected function getSeekerId(int $userId): ?int
    {
        $seekerModel = model(JobSeekerModel::class);
        $seeker = $seekerModel->where('user_id', $userId)->first();
        return $seeker ? $seeker->id : null;
    }
}
