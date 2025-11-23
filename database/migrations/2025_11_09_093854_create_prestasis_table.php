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
        Schema::create('prestasis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('santri_id')->index('prestasis_santri_id_foreign');
            $table->string('nama_prestasi');
            $table->integer('poin');
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('dicatat_oleh_id')->index('prestasis_dicatat_oleh_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestasis');
    }
};
