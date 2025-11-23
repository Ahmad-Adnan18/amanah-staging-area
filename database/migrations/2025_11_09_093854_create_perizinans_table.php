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
        Schema::create('perizinans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('santri_id')->index('perizinans_santri_id_foreign');
            $table->string('jenis_izin');
            $table->string('kategori');
            $table->text('keterangan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_akhir')->nullable();
            $table->enum('status', ['aktif', 'selesai', 'terlambat'])->default('aktif');
            $table->unsignedBigInteger('created_by')->index('perizinans_created_by_foreign');
            $table->unsignedBigInteger('updated_by')->nullable()->index('perizinans_updated_by_foreign');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perizinans');
    }
};
