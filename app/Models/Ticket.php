<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Concerns\HasSla;

class Ticket extends Model
{
    use SoftDeletes;
    use HasSla;

    protected $table = 'tickets';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'kategori',
        'sub_kategori',
        'deskripsi_masalah',
        'nomor_bmn',
        'attachment_foto',
        'prioritas',
        'status',
        'penanggung_jawab',
        'pj_id',
        'tanggal_selesai',
        'hasil_resolved_foto',
        'survei_kepuasan',
        'closed_by',
        'admin_notif_user_closed_read',
        'admin_notif_new_ticket_read',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    protected $casts = [
        'tanggal_selesai' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'waktu_mulai_dikerjakan' => 'datetime',
        'admin_notif_new_ticket_read' => 'boolean',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(TicketMessage::class, 'ticket_id')->orderBy('created_at', 'asc');
    }

    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }

    public function pj(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'pj_id', 'id');
    }

    public function collaborators()
    {
        return $this->hasMany(TicketCollaborator::class, 'ticket_id');
    }

    public function isCollaborator(int $pjId): bool
    {
        return $this->collaborators()->where('pj_id', $pjId)->exists();
    }
}