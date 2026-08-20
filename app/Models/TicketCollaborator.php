<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketCollaborator extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'ticket_id',
        'pj_id',
        'invited_by',
        'invitation_read',
        'closed_notif_read',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'invitation_read' => 'boolean',
        'closed_notif_read' => 'boolean',
    ];

    public function pj()
    {
        return $this->belongsTo(Users::class, 'pj_id');
    }

    public function inviter()
    {
        return $this->belongsTo(Users::class, 'invited_by');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }
}