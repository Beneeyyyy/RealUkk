<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;

class Guru extends Authenticatable
{
    use Notifiable;
    use HasFactory;
    use HasRoles;

    protected $fillable = [
        'name',
        'nip',
        'gender',
        'alamat',
        'kontak',
        'email',
        'password',
    ];

     protected $hidden = [
        'password',
        'remember_token',
    ];
}
