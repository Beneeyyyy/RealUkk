<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Industri extends Model
{
    $table->id();
    $table->string('nama');
    $table->string('bidang_usaha');           // Bidang usaha industri
    $table->text('alamat');                   // Alamat industri
    $table->string('kontak');                 // Nomor kontak
    $table->string('email')->unique();        =
    $table->timestamps();
}
