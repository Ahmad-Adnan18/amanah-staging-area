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
        Schema::dropIfExists('teacher_unavailabilities');

        // 2. Buat kembali tabel dengan struktur yang 100% benar
        Schema::create('teacher_unavailabilities', function (Blueprint $table) {
            $table->id();

            // Foreign key yang benar ke tabel 'teachers'
            $table->foreignId('teacher_id')
                  ->constrained('teachers')
                  ->onDelete('cascade');

            $table->tinyInteger('day_of_week')->comment('1 for Sabtu, ... 6 for Kamis');
            $table->tinyInteger('time_slot')->comment('Jam ke- (1, 2, 3, etc)');
            $table->timestamps();

            // Unique constraint yang benar
            $table->unique(['teacher_id', 'day_of_week', 'time_slot']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_unavailabilities');
    }
};