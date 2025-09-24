<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Mengubah tabel untuk membuat kolom teacher_id menjadi opsional (nullable)
        Schema::table('kelas_mata_pelajaran', function (Blueprint $table) {
            // Perintah ->nullable()->change() akan mengubah kolom yang sudah ada
            $table->foreignId('teacher_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Perintah ini untuk membatalkan perubahan jika diperlukan
        Schema::table('kelas_mata_pelajaran', function (Blueprint $table) {
            $table->foreignId('teacher_id')->nullable(false)->change();
        });
    }
};
