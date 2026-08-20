<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketCollaborator extends Model
{
    public $timestamps = false;
    protected $fillable = ['ticket_id', 'pj_id', 'invited_by', 'created_at'];

    public function pj()
    {
        return $this->belongsTo(Users::class, 'pj_id');
    }

    public function inviter()
    {
        return $this->belongsTo(Users::class, 'invited_by');
    }
}