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
        Schema::create('teacher_unavailabilities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('teacher_id');
            $table->tinyInteger('day_of_week')->comment('1 for Sabtu, ... 6 for Kamis');
            $table->tinyInteger('time_slot')->comment('Jam ke- (1, 2, 3, etc)');
            $table->timestamps();

            $table->unique(['teacher_id', 'day_of_week', 'time_slot']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_unavailabilities');
    }
};
