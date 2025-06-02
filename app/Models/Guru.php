<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $fillable = [
        'name',
        'nip',
        'gender',
        'address',
        'contact',
        'email',
        'password',
    ];
}
