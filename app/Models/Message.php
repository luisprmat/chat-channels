<?php

namespace App\Models;

use App\Casts\Markdown;
use Illuminate\Database\Eloquent\Attributes\Unguarded;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Unguarded]
class Message extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'content' => Markdown::class,
            'sent_at' => 'datetime:H:i',
        ];
    }

    /**
     * The user who sent the message.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The channel the message was sent to.
     */
    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }
}
