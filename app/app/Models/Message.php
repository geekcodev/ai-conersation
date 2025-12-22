<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


/**
 * @property string $id
 * @property string $conversation_id
 * @property string $role
 * @property string $content
 * @property string $timestamp
 *
 * @property Conversation $conversation
 */
class Message extends Model
{
    const string CREATED_AT = 'timestamp';
    const string UPDATED_AT = 'timestamp';

    use HasUuids;

    protected $table = 'message';

    protected $keyType = 'string';

    protected $primaryKey = 'id';

    protected $fillable = [
        'conversation_id',
        'role',
        'content',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }
}
