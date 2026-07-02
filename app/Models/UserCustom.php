<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCustom extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'username';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];
}