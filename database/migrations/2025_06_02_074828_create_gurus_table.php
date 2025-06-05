<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gurus', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama Guru
            $table->string('nip')->unique(); // Nomor Induk Pegawai
            $table->string('gender', 10);
            $table->text('alamat');
            $table->string('kontak');
            $table->string('email')->unique();
            $table->string('password'); // Password untuk login
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gurus');
    }
};
