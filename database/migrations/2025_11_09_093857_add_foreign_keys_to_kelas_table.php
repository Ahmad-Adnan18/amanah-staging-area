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
        Schema::table('kelas', function (Blueprint $table) {
            $table->foreign(['kurikulum_template_id'])->references(['id'])->on('kurikulum_templates')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['room_id'])->references(['id'])->on('rooms')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropForeign('kelas_kurikulum_template_id_foreign');
            $table->dropForeign('kelas_room_id_foreign');
        });
    }
};
