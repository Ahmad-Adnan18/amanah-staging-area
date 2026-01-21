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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['info', 'warning', 'success', 'urgent'])->default('info');
            $table->json('target_roles')->nullable(); // null = semua role (kecuali wali_santri)
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('published_at')->nullable(); // null = draft
            $table->timestamp('expires_at')->nullable(); // null = tidak expired
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
