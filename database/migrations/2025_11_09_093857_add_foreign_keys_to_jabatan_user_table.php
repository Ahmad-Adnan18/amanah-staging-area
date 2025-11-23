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
        Schema::table('jabatan_user', function (Blueprint $table) {
            $table->foreign(['jabatan_id'])->references(['id'])->on('jabatans')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['kelas_id'])->references(['id'])->on('kelas')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jabatan_user', function (Blueprint $table) {
            $table->dropForeign('jabatan_user_jabatan_id_foreign');
            $table->dropForeign('jabatan_user_kelas_id_foreign');
            $table->dropForeign('jabatan_user_user_id_foreign');
        });
    }
};
