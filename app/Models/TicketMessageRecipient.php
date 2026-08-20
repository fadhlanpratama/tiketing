<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketMessageRecipient extends Model
{
    protected $fillable = [
        'ticket_message_id',
        'user_id',
        'read',
    ];

    protected $casts = [
        'read' => 'boolean',
    ];

    public function message()
    {
        return $this->belongsTo(TicketMessage::class, 'ticket_message_id');
    }
}