<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use App\Factories\GoogleAIServiceFactory;
use App\Repositories\MessageRepository;
use App\Repositories\ConversationRepository;


class ChatController extends Controller
{
    private ConversationRepository $conversationRepository;
    private MessageRepository $messageRepository;
    private GoogleAIServiceFactory $googleAIServiceFactory;

    public function __construct(
        ConversationRepository $conversationRepository,
        MessageRepository      $messageRepository,
        GoogleAIServiceFactory $googleAIServiceFactory
    )
    {
        $this->conversationRepository = $conversationRepository;
        $this->messageRepository = $messageRepository;
        $this->googleAIServiceFactory = $googleAIServiceFactory;
    }

    private function getSessionId(?string $sessionId = null)
    {
        return !empty($sessionId) ? $sessionId : (session()->getId() ?? Str::random(40));
    }

    public function sendMessage(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'session_id' => 'required|string',
            'message' => 'required|string|max:4000',
        ]);
        $sessionId = $this->getSessionId($validated['session_id']);
        $conversation = $this->conversationRepository->findOrCreateBySessionId(sessionId: $sessionId);
        $lastMessage = $this->messageRepository->create([
            'conversation_id' => $conversation->id,
            'role' => 'user',
            'content' => $validated['message'],
        ]);
        $history = $this->messageRepository->getHistoryByConversationId(conversationId: $lastMessage->conversation_id);
        $googleAiService = $this->googleAIServiceFactory->create(history: $history);
        $aiResponse = $googleAiService->getResponse($lastMessage->content);
        $candidates = $aiResponse['candidates'][0]['content'] ?? null;
        $responseText = $candidates['parts'][0]['text'] ?? null;
        $role = $candidates['role'] ?? null;
        if (!empty($responseText) && !empty($role)) {
            $lastMessage = $this->messageRepository->create([
                'conversation_id' => $conversation->id,
                'role' => $role,
                'content' => $responseText,
            ]);
        }
        return response()->json(
            data: [
                'success' => true,
                'data' => [
                    'message' => $lastMessage->content,
                    'message_id' => $lastMessage->id,
                    'timestamp' => $lastMessage->timestamp->toIso8601String(),
                ],
            ],
            options: JSON_UNESCAPED_UNICODE
        );
    }

    public function getHistory(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'session_id' => 'required|string',
        ]);
        $sessionId = $this->getSessionId($validated['session_id']);
        $conversation = $this->conversationRepository->findBySessionId($sessionId);
        if (!$conversation) {
            return response()->json([
                'success' => true,
                'data' => ['messages' => []],
            ]);
        }
        $messages = $this->messageRepository->getHistoryByConversationId($conversation->id);
        return response()->json([
            'success' => true,
            'data' => ['messages' => $messages],
        ]);
    }
}
