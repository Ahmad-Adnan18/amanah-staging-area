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
        Schema::table('catatan_harians', function (Blueprint $table) {
            $table->foreign(['dicatat_oleh_id'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['santri_id'])->references(['id'])->on('santris')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catatan_harians', function (Blueprint $table) {
            $table->dropForeign('catatan_harians_dicatat_oleh_id_foreign');
            $table->dropForeign('catatan_harians_santri_id_foreign');
        });
    }
};
