<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use App\Base\BaseRepository;
use App\Models\Conversation;


class ConversationRepository extends BaseRepository
{
    protected string $modelClass = Conversation::class;

    /**
     * @return Conversation
     */
    protected function getModel(): Model
    {
        return new $this->modelClass;
    }

    /**
     * @param array $data
     * @return Conversation
     */
    public function create(array $data): Conversation
    {
        return $this->modelClass::create($data);
    }

    /**
     * @param array $data
     * @param Conversation $conversation
     * @return bool
     */
    public function update(array $data, Conversation $conversation): bool
    {
        return $conversation->update($data);
    }

    /**
     * @param Conversation $conversation
     * @return bool
     */
    public function delete(Conversation $conversation): bool
    {
        return $conversation->delete();
    }

    /**
     * @param int $id
     * @return Conversation|null
     */
    public function findById(int $id): ?Conversation
    {
        return $this->modelClass::find($id);
    }

    /**
     * @param string $sessionId
     * @return Conversation|null
     */
    public function findBySessionId(string $sessionId): ?Conversation
    {
        return $this->modelClass::where('session_id', $sessionId)->first();
    }

    /**
     * @param string $sessionId
     * @return Conversation
     */
    public function findOrCreateBySessionId(string $sessionId): Conversation
    {
        return $this->modelClass::firstOrCreate(['session_id' => $sessionId]);
    }
}
