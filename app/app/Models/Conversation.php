<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


/**
 * @property string $id
 * @property string $session_id
 * @property string $timestamp
 *
 * @property Message[] $messages
 */
class Conversation extends Model
{
    const string CREATED_AT = 'timestamp';
    const string UPDATED_AT = 'timestamp';

    use HasUuids;

    protected $table = 'conversation';

    protected $keyType = 'string';

    protected $primaryKey = 'id';

    protected $fillable = [
        'session_id',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
