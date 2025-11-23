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
        Schema::create('schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('kelas_id')->index('schedules_kelas_id_foreign');
            $table->unsignedBigInteger('mata_pelajaran_id')->index('schedules_mata_pelajaran_id_foreign');
            $table->unsignedBigInteger('teacher_id')->index('schedules_teacher_id_foreign');
            $table->unsignedBigInteger('room_id')->index('schedules_room_id_foreign');
            $table->tinyInteger('day_of_week');
            $table->tinyInteger('time_slot');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
