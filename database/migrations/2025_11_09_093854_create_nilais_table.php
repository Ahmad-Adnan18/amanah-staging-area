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
        Schema::create('nilais', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('santri_id');
            $table->unsignedBigInteger('kelas_id')->index('nilais_kelas_id_foreign');
            $table->unsignedBigInteger('mata_pelajaran_id')->index('nilais_mata_pelajaran_id_foreign');
            $table->unsignedInteger('nilai_tugas')->nullable();
            $table->unsignedInteger('nilai_uts')->nullable();
            $table->unsignedInteger('nilai_uas')->nullable();
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->string('tahun_ajaran', 9);
            $table->unsignedBigInteger('created_by')->index('nilais_created_by_foreign');
            $table->unsignedBigInteger('updated_by')->nullable()->index('nilais_updated_by_foreign');
            $table->timestamps();

            $table->unique(['santri_id', 'mata_pelajaran_id', 'semester', 'tahun_ajaran']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};
