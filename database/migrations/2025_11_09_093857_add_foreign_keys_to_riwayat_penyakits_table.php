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
        Schema::table('riwayat_penyakits', function (Blueprint $table) {
            $table->foreign(['dicatat_oleh'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['santri_id'])->references(['id'])->on('santris')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayat_penyakits', function (Blueprint $table) {
            $table->dropForeign('riwayat_penyakits_dicatat_oleh_foreign');
            $table->dropForeign('riwayat_penyakits_santri_id_foreign');
        });
    }
};
