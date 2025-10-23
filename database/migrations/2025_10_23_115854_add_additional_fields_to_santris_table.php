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
            Schema::table('santris', function (Blueprint $table) {
                $table->date('tanggal_lahir')->nullable()->after('jenis_kelamin');
                $table->string('tempat_lahir')->nullable()->after('jenis_kelamin');
                $table->string('agama')->nullable()->after('tempat_lahir');
                $table->text('alamat')->nullable()->after('agama');
                $table->string('no_telepon')->nullable()->after('alamat');
                $table->string('email')->nullable()->after('no_telepon');
                $table->string('asal_sekolah')->nullable()->after('email');
                $table->string('nama_ayah')->nullable()->after('asal_sekolah');
                $table->string('nama_ibu')->nullable()->after('nama_ayah');
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::table('santris', function (Blueprint $table) {
                $table->dropColumn([
                    'tanggal_lahir',
                    'tempat_lahir',
                    'agama',
                    'alamat',
                    'no_telepon',
                    'email',
                    'asal_sekolah',
                    'nama_ayah',
                    'nama_ibu'
                ]);
            });
        }
    };
