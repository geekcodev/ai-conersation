<?php

declare(strict_types=1);

namespace App\Services;

use Gemini;
use Gemini\Data\Content;
use Gemini\Enums\Role;
use Gemini\Client;
use Gemini\Resources\ChatSession;


class GoogleAIService
{
    private Client $client;
    private ChatSession $chat;

    public function __construct(
        array        $history = [],
    )
    {
        $apiKey = config('services.google_ai.api_key');
        $model = config('services.google_ai.model', 'gemini-2.0-flash');

        $this->client = Gemini::client($apiKey);
        $this->chat = $this->client->generativeModel($model)->startChat($this->prepareHistory($history));
    }

    private function prepareHistory(array $history): array
    {
        $result = [];
        foreach ($history as $message) {
            if (!empty($message['content']) && !empty($message['role'])) {
                $result[] = Content::parse(part: $message['content'], role: $message['role'] == 'user' ? Role::USER : Role::MODEL);
            }
        }
        return $result;
    }

    public function getResponse(string $message): array
    {
        $request = $this->chat->sendMessage($message);
        return $request->toArray();
    }
}
