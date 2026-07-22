<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Users extends Authenticatable
{
    use Notifiable,SoftDeletes;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;
    protected $fillable = [
        'password',
        'nama_lengkap',
        'email',
        'divisi',
        'no_telp',
        'role',
        'status',
    ];
    protected $hidden = [
        'password',
    ];
    protected $casts = [
        'password' => 'hashed',
    ];
}