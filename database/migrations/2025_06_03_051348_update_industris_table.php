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
        Schema::table('industris', function (Blueprint $table) {
            if (!Schema::hasColumn('industris', 'nama')) {
                $table->string('nama');
            }
            if (!Schema::hasColumn('industris', 'bidang_usaha')) {
                $table->string('bidang_usaha');
            }
            if (!Schema::hasColumn('industris', 'alamat')) {
                $table->text('alamat');
            }
            if (!Schema::hasColumn('industris', 'kontak')) {
                $table->string('kontak');
            }
            if (!Schema::hasColumn('industris', 'email')) {
                $table->string('email')->unique();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('industris', function (Blueprint $table) {
            //
        });
    }
};
