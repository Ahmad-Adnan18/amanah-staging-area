<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('setoran_tahfidz', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('santri_id')->constrained('santris')->cascadeOnDelete();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
            $table->foreignId('surat_id')->constrained('surats')->cascadeOnDelete();

            $table->date('tanggal_setor');
            $table->enum('jenis_setoran', ['baru', 'murojaah']);
            $table->integer('ayat_mulai');
            $table->integer('ayat_selesai');
            $table->enum('nilai', ['mumtaz', 'jayyid_jiddan', 'jayyid', 'maqbul']);
            $table->text('keterangan')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('setoran_tahfidz');
    }
};