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
        Schema::table('kelas', function (Blueprint $table) {
            // Menambahkan kolom 'tingkatan' setelah kolom 'nama_kelas'
            // Dibuat nullable() agar data lama tidak error
            $table->string('tingkatan')->nullable()->after('nama_kelas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            // Kode untuk membatalkan perubahan jika diperlukan
            $table->dropColumn('tingkatan');
        });
    }
};
