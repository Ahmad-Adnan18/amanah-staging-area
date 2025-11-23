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
        Schema::create('absensis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('santri_id');
            $table->unsignedBigInteger('kelas_id')->index('absensis_kelas_id_foreign');
            $table->unsignedBigInteger('schedule_id')->index('absensis_schedule_id_foreign');
            $table->unsignedBigInteger('teacher_id')->nullable()->index('absensis_teacher_id_foreign');
            $table->date('tanggal');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alfa'])->default('hadir');
            $table->string('keterangan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index('absensis_created_by_foreign');
            $table->unsignedBigInteger('updated_by')->nullable()->index('absensis_updated_by_foreign');
            $table->timestamps();

            $table->unique(['santri_id', 'schedule_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
