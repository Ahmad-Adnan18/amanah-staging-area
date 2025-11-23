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
        Schema::create('riwayat_penyakits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('santri_id')->index('riwayat_penyakits_santri_id_foreign');
            $table->string('nama_penyakit');
            $table->text('keterangan')->nullable();
            $table->date('tanggal_diagnosis');
            $table->string('status')->default('aktif');
            $table->text('penanganan')->nullable();
            $table->unsignedBigInteger('dicatat_oleh')->index('riwayat_penyakits_dicatat_oleh_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_penyakits');
    }
};
