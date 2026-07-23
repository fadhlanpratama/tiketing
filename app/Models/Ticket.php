<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use SoftDeletes;

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
        'tanggal_selesai',
        'hasil_resolved_foto',
        'survei_kepuasan',
        'closed_by',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    protected $casts = [
        'tanggal_selesai' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(TicketMessage::class, 'ticket_id')->orderBy('created_at', 'asc');
    }

    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
}