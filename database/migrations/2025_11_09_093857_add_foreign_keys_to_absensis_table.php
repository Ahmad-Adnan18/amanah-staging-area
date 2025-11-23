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
        Schema::table('absensis', function (Blueprint $table) {
            $table->foreign(['created_by'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['kelas_id'])->references(['id'])->on('kelas')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['santri_id'])->references(['id'])->on('santris')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['schedule_id'])->references(['id'])->on('schedules')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['teacher_id'])->references(['id'])->on('teachers')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['updated_by'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropForeign('absensis_created_by_foreign');
            $table->dropForeign('absensis_kelas_id_foreign');
            $table->dropForeign('absensis_santri_id_foreign');
            $table->dropForeign('absensis_schedule_id_foreign');
            $table->dropForeign('absensis_teacher_id_foreign');
            $table->dropForeign('absensis_updated_by_foreign');
        });
    }
};
