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
        Schema::table('schedules', function (Blueprint $table) {
            $table->foreign(['kelas_id'])->references(['id'])->on('kelas')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['mata_pelajaran_id'])->references(['id'])->on('mata_pelajarans')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['room_id'])->references(['id'])->on('rooms')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['teacher_id'])->references(['id'])->on('teachers')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign('schedules_kelas_id_foreign');
            $table->dropForeign('schedules_mata_pelajaran_id_foreign');
            $table->dropForeign('schedules_room_id_foreign');
            $table->dropForeign('schedules_teacher_id_foreign');
        });
    }
};
