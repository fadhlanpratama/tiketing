<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketMessage extends Model
{
    protected $table = 'ticket_messages';

    protected $fillable = [
        'ticket_id',
        'sender_type',
        'sender_nama',
        'pesan',
        'foto',
        'read_by_pj',
        'read_by_user',
    ];

    protected $casts = [
        'read_by_pj'   => 'boolean',
        'read_by_user' => 'boolean',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function recipients()
    {
        return $this->hasMany(TicketMessageRecipient::class, 'ticket_message_id');
    }
} 