<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meetup extends Model
{
    protected $fillable = [
        'conversation_id',
        'proposed_by',
        'location',
        'scheduled_at',
        'status',
        'note',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function proposer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'proposed_by');
    }
}
