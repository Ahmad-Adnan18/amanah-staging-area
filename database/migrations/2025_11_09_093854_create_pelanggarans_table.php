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
        Schema::create('pelanggarans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('santri_id')->index('pelanggarans_santri_id_foreign');
            $table->string('jenis_pelanggaran');
            $table->date('tanggal_kejadian');
            $table->text('keterangan')->nullable();
            $table->string('dicatat_oleh');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggarans');
    }
};
