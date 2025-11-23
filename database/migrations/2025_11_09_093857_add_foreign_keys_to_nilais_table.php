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
        Schema::table('nilais', function (Blueprint $table) {
            $table->foreign(['created_by'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['kelas_id'])->references(['id'])->on('kelas')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['mata_pelajaran_id'])->references(['id'])->on('mata_pelajarans')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['santri_id'])->references(['id'])->on('santris')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['updated_by'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilais', function (Blueprint $table) {
            $table->dropForeign('nilais_created_by_foreign');
            $table->dropForeign('nilais_kelas_id_foreign');
            $table->dropForeign('nilais_mata_pelajaran_id_foreign');
            $table->dropForeign('nilais_santri_id_foreign');
            $table->dropForeign('nilais_updated_by_foreign');
        });
    }
};
