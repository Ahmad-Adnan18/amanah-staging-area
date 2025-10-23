<?php

namespace App\Providers;

use App\Models\Perizinan;
use App\Models\RiwayatPenyakit;
use App\Models\Santri;
use App\Models\Kelas;
use App\Models\User;
use App\Models\Pelanggaran;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Jabatan;
use App\Models\CatatanHarian;
use App\Models\Prestasi;
use App\Models\Absensi;
use App\Models\RekapanHarian;
use App\Policies\PerizinanPolicy;
use App\Policies\RiwayatPenyakitPolicy;
use App\Policies\SantriPolicy;
use App\Policies\KelasPolicy;
use App\Policies\UserPolicy;
use App\Policies\PelanggaranPolicy;
use App\Policies\MataPelajaranPolicy;
use App\Policies\NilaiPolicy;
use App\Policies\JabatanPolicy;
use App\Policies\CatatanHarianPolicy;
use App\Policies\PrestasiPolicy;
use App\Policies\AbsensiPolicy;
use App\Policies\RekapanHarianPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Daftarkan policy di sini
        Santri::class => SantriPolicy::class,
        Perizinan::class => PerizinanPolicy::class,
        Kelas::class => KelasPolicy::class,
        User::class => UserPolicy::class,
        Pelanggaran::class => PelanggaranPolicy::class,
        MataPelajaran::class => MataPelajaranPolicy::class,
        Nilai::class => NilaiPolicy::class,
        Jabatan::class => JabatanPolicy::class,
        CatatanHarian::class => CatatanHarianPolicy::class,
        Prestasi::class => PrestasiPolicy::class,
        Absensi::class => AbsensiPolicy::class,
        RekapanHarian::class => RekapanHarianPolicy::class,
        RiwayatPenyakit::class => RiwayatPenyakitPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
