<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketNotificationStatus extends Model
{
    protected $table = 'ticket_notification_statuses';

    protected $fillable = [
        'ticket_id',
        'user_id',
        'role',
        'key',
        'read',
    ];

    protected $casts = [
        'read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'user_id');
    }

    public static function forTicket(Ticket $ticket, ?int $userId, string $role, string $key): ?self
    {
        return self::query()
            ->where('ticket_id', $ticket->getKey())
            ->where('role', $role)
            ->where('key', $key)
            ->when($userId !== null, fn ($query) => $query->where('user_id', $userId))
            ->when($userId === null, fn ($query) => $query->whereNull('user_id'))
            ->first();
    }

    public static function markRead(Ticket $ticket, ?int $userId, string $role, string $key, bool $read = true): self
    {
        return self::updateOrCreate(
            [
                'ticket_id' => $ticket->getKey(),
                'user_id' => $userId,
                'role' => $role,
                'key' => $key,
            ],
            ['read' => $read]
        );
    }
}
