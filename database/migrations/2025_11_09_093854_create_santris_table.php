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
        Schema::create('santris', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('wali_id')->nullable()->index('santris_wali_id_foreign');
            $table->string('kode_registrasi_wali')->nullable()->unique();
            $table->string('nis')->nullable()->unique();
            $table->string('nama');
            $table->enum('jenis_kelamin', ['Putra', 'Putri']);
            $table->string('tempat_lahir')->nullable();
            $table->string('agama')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('asal_sekolah')->nullable();
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('rayon')->nullable();
            $table->unsignedBigInteger('kelas_id')->index('santris_kelas_id_foreign');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('santris');
    }
};
