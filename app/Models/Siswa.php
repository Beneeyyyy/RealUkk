<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;

class Siswa extends Authenticatable
{
    use  Notifiable;
    use HasFactory;
    use HasRoles;

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
