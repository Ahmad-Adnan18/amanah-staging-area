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
        Schema::table('schedule_substitutions', function (Blueprint $table) {
            $table->foreign(['created_by'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['original_teacher_id'])->references(['id'])->on('teachers')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['schedule_id'])->references(['id'])->on('schedules')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['substitute_teacher_id'])->references(['id'])->on('teachers')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedule_substitutions', function (Blueprint $table) {
            $table->dropForeign('schedule_substitutions_created_by_foreign');
            $table->dropForeign('schedule_substitutions_original_teacher_id_foreign');
            $table->dropForeign('schedule_substitutions_schedule_id_foreign');
            $table->dropForeign('schedule_substitutions_substitute_teacher_id_foreign');
        });
    }
};
