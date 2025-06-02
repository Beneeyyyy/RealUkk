<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Siswa extends Authenticatable
{
    use  Notifiable;

    protected $fillable = [
        'nama',
        'nis',
        'gender',
        'alamat',
        'kontak',
        'email',
        'gambar',
        'password',
        'status_pkl',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
