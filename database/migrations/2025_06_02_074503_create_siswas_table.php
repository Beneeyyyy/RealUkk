<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nis')->unique();
            $table->string('gender', 10);
            $table->text('alamat');
            $table->string('kontak');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('gambar')->nullable();
            $table->boolean('status_pkl')->default(false);
            $table->rememberToken(); // untuk fitur "remember me"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
