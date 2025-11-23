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
        Schema::create('schedule_substitutions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('schedule_id');
            $table->unsignedBigInteger('original_teacher_id')->index('schedule_substitutions_original_teacher_id_foreign');
            $table->unsignedBigInteger('substitute_teacher_id')->index('schedule_substitutions_substitute_teacher_id_foreign');
            $table->date('substitution_date');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index('schedule_substitutions_created_by_foreign');
            $table->timestamps();

            $table->unique(['schedule_id', 'substitution_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_substitutions');
    }
};
