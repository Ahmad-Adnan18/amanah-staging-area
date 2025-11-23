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
        Schema::create('kelas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_kelas')->unique();
            $table->string('tingkatan')->nullable();
            $table->integer('total_jp_sehari')->default(7);
            $table->boolean('is_active_for_scheduling')->default(true);
            $table->integer('level')->nullable()->comment('Tingkat kelas, cth: 7, 8, 9');
            $table->string('parallel_name')->nullable()->comment('Nama paralel, cth: A, B, Putra A');
            $table->unsignedBigInteger('kurikulum_template_id')->nullable()->index('kelas_kurikulum_template_id_foreign');
            $table->unsignedBigInteger('room_id')->nullable()->index('kelas_room_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
