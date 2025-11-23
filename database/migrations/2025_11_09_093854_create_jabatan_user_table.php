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
        Schema::create('jabatan_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('jabatan_user_user_id_foreign');
            $table->unsignedBigInteger('kelas_id')->index('jabatan_user_kelas_id_foreign');
            $table->unsignedBigInteger('jabatan_id')->index('jabatan_user_jabatan_id_foreign');
            $table->string('tahun_ajaran', 9);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jabatan_user');
    }
};
