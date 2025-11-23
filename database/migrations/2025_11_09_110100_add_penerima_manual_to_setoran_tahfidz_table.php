<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('setoran_tahfidz', function (Blueprint $table) {
            // 1. Tambahkan kolom baru untuk nama manual, letakkan setelah 'nilai'
            $table->string('penerima_manual')->nullable()->after('nilai');
            
            // 2. Buat kolom teacher_id boleh kosong (nullable)
            // Pastikan Anda memiliki "doctrine/dbal"
            // composer require doctrine/dbal
            $table->foreignId('teacher_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('setoran_tahfidz', function (Blueprint $table) {
            $table->dropColumn('penerima_manual');
            $table->foreignId('teacher_id')->nullable(false)->change(); // Kembalikan seperti semula
        });
    }
};