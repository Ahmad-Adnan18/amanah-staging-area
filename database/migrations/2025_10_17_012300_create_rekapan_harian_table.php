<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rekapan_harian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained()->onDelete('cascade');
            $table->foreignId('santri_id')->constrained()->onDelete('cascade');
            $table->date('tanggal');

            // Status per jam pelajaran (1-7)
            $table->tinyInteger('jam_1')->default(0); // 0=alfa, 1=hadir, 2=sakit, 3=izin
            $table->tinyInteger('jam_2')->default(0);
            $table->tinyInteger('jam_3')->default(0);
            $table->tinyInteger('jam_4')->default(0);
            $table->tinyInteger('jam_5')->default(0);
            $table->tinyInteger('jam_6')->default(0);
            $table->tinyInteger('jam_7')->default(0);

            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();

            // Unique constraint untuk mencegah duplikasi
            $table->unique(['santri_id', 'tanggal']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('rekapan_harian');
    }
};
