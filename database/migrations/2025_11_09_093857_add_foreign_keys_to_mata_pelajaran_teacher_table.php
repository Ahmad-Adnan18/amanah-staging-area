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
        Schema::table('mata_pelajaran_teacher', function (Blueprint $table) {
            $table->foreign(['mata_pelajaran_id'])->references(['id'])->on('mata_pelajarans')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['teacher_id'])->references(['id'])->on('teachers')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mata_pelajaran_teacher', function (Blueprint $table) {
            $table->dropForeign('mata_pelajaran_teacher_mata_pelajaran_id_foreign');
            $table->dropForeign('mata_pelajaran_teacher_teacher_id_foreign');
        });
    }
};
