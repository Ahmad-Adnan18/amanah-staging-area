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
        Schema::create('rekapan_harian', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('kelas_id')->index('rekapan_harian_kelas_id_foreign');
            $table->unsignedBigInteger('santri_id');
            $table->date('tanggal');
            $table->tinyInteger('jam_1')->default(1);
            $table->tinyInteger('jam_2')->default(1);
            $table->tinyInteger('jam_3')->default(1);
            $table->tinyInteger('jam_4')->default(1);
            $table->tinyInteger('jam_5')->default(1);
            $table->tinyInteger('jam_6')->default(1);
            $table->tinyInteger('jam_7')->default(1);
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('created_by')->index('rekapan_harian_created_by_foreign');
            $table->unsignedBigInteger('updated_by')->index('rekapan_harian_updated_by_foreign');
            $table->timestamps();

            $table->unique(['santri_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekapan_harian');
    }
};
