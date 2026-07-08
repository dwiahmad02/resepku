<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class TbUser extends Authenticatable
{
    use Notifiable;

    // Nama tabel di database (bukan default 'tb_users', tapi 'tb_user')
    protected $table = 'tb_user';

    // Tabel ini tidak punya kolom 'updated_at'
    const UPDATED_AT = null;

    // Tabel ini tidak punya kolom 'remember_token'
    public function getRememberTokenName()
    {
        return null;
    }

    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}