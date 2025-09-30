<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Hapus semua perintah dropForeign karena foreign key tidak ada

            // LANGKAH 1: Langsung ubah kolom yang ada menjadi nullable
            $table->unsignedBigInteger('teacher_id')->nullable()->change();

            // LANGKAH 2: Langsung tambahkan foreign key yang benar
            $table->foreign('teacher_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Logika untuk mengembalikan perubahan
            $table->dropForeign(['teacher_id']);
            
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
        });
    }
};