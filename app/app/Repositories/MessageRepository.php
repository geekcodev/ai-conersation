<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use App\Base\BaseRepository;
use App\Models\Message;


class MessageRepository extends BaseRepository
{
    protected string $modelClass = Message::class;

    /**
     * @return Message
     */
    protected function getModel(): Model
    {
        return new $this->modelClass;
    }

    /**
     * @param array $data
     * @return Message
     */
    public function create(array $data): Message
    {
        return $this->modelClass::create($data);
    }

    /**
     * @param array $data
     * @param Message $message
     * @return bool
     */
    public function update(array $data, Message $message): bool
    {
        return $message->update($data);
    }

    /**
     * @param Message $message
     * @return bool
     */
    public function delete(Message $message): bool
    {
        return $message->delete();
    }

    /**
     * @param int $id
     * @return Message|null
     */
    public function findById(int $id): ?Message
    {
        return $this->modelClass::find($id);
    }

    /**
     * @param string $conversationId
     * @return Message|null
     */
    public function findByConversationId(string $conversationId): ?Message
    {
        return $this->modelClass::where('conversation_id', $conversationId)
            ->orderBy('timestamp', 'desc')
            ->first();
    }

    public function findByConversationIdAndRole(string $conversationId, string $role): ?Message
    {
        return $this->modelClass::where('conversation_id', $conversationId)
            ->where('role', $role)
            ->orderBy('timestamp', 'desc')
            ->first();
    }

    /**
     * @param string $conversationId
     * @return array
     */
    public function getHistoryByConversationId(string $conversationId): array
    {
        return $this->modelClass::where('conversation_id', $conversationId)
            ->orderBy('timestamp')
            ->get()
            ->map(fn($msg) => [
                'id' => $msg->id,
                'role' => $msg->role,
                'content' => $msg->content,
                'timestamp' => $msg->timestamp->toIso8601String(),
            ])
            ->toArray();
    }
}
