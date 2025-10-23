<?php

// database/migrations/xxxx_xx_xx_create_riwayat_penyakits_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('riwayat_penyakits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained()->onDelete('cascade');
            $table->string('nama_penyakit');
            $table->text('keterangan')->nullable();
            $table->date('tanggal_diagnosis');
            $table->string('status')->default('aktif'); // aktif, sembuh, kronis
            $table->text('penanganan')->nullable();
            $table->foreignId('dicatat_oleh')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('riwayat_penyakits');
    }
};
