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
        // 1. Hapus tabel lama jika ada
        Schema::dropIfExists('schedules');

        // 2. Buat kembali tabel dengan struktur yang 100% benar
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajarans')->onDelete('cascade');
            
            // Foreign key yang benar ke tabel 'teachers'
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->tinyInteger('day_of_week');
            $table->tinyInteger('time_slot');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
};