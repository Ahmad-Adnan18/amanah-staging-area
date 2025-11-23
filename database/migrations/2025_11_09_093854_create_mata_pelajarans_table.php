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
        Schema::create('mata_pelajarans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_pelajaran');
            $table->string('tingkatan')->default('Umum');
            $table->integer('duration_jp')->comment('Durasi dalam Jam Pelajaran (JP)');
            $table->boolean('requires_special_room')->default(false)->comment('Apakah butuh ruang khusus seperti lab');
            $table->enum('kategori', ['Umum', 'Diniyah']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_pelajarans');
    }
};
