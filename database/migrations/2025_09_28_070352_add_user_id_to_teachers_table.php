    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up()
        {
            Schema::table('teachers', function (Blueprint $table) {
                // Menambahkan kolom user_id yang bisa null (opsional)
                // Foreign key ke tabel users, jika user dihapus, set user_id di sini menjadi null.
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            });
        }

        public function down()
        {
            Schema::table('teachers', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }
    };
  
