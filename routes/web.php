<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Pengajaran\KelasController;
use App\Http\Controllers\Pengajaran\SantriController;
use App\Http\Controllers\Perizinan\PerizinanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PelanggaranController;
use App\Http\Controllers\Pengajaran\MataPelajaranController;
use App\Http\Controllers\Akademik\NilaiController;
use App\Http\Controllers\SantriProfileController;
use App\Http\Controllers\Auth\WaliRegistrationController;
use App\Http\Controllers\Pengajaran\JabatanController;
use App\Http\Controllers\Pengajaran\RekapanHarianController;
use App\Http\Controllers\Keasramaan\CatatanHarianController;
use App\Http\Controllers\Keasramaan\PrestasiController;
use App\Http\Controllers\Akademik\PlacementController;
use App\Http\Controllers\Admin\SantriManagementController;
use App\Http\Controllers\Admin\MasterData\RoomController;
use App\Http\Controllers\Admin\MasterData\TeacherAvailabilityController;
use App\Http\Controllers\Admin\Configuration\RuleController;
use App\Http\Controllers\Admin\Scheduling\ScheduleGeneratorController;
use App\Http\Controllers\Admin\Scheduling\ScheduleViewController;
use App\Http\Controllers\PublicScheduleController;
// TAMBAHKAN USE STATEMENT UNTUK CONTROLLER GURU YANG BARU
use App\Http\Controllers\Admin\MasterData\TeacherController;
use App\Http\Controllers\Admin\MasterData\InventoryItemController;
use App\Http\Controllers\Pengajaran\AbsensiController;
use App\Http\Controllers\Admin\Scheduling\ScheduleManualController;
use App\Http\Controllers\Admin\AppSettingController;
// TAMBAHKAN USE STATEMENT UNTUK CONTROLLER BARU
use App\Http\Controllers\Kesehatan\RiwayatPenyakitController;

Route::get('/', function () {
    return redirect()->route('login');
});

// RUTE BARU UNTUK REGISTRASI WALI
Route::get('/wali-register', [WaliRegistrationController::class, 'create'])->name('wali.register');
Route::post('/wali-register', [WaliRegistrationController::class, 'store']);

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/jadwal-saya', [DashboardController::class, 'jadwalSaya'])
    ->name('jadwal.saya')
    ->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // RUTE UNTUK PROFIL DAN PORTOFOLIO SANTRI
    Route::prefix('santri')->name('santri.')->group(function () {
        // Profil Santri (menggunakan view santri-profile.show)
        Route::get('/{santri}/profil', [SantriProfileController::class, 'show'])->name('profil.show');
        Route::post('/{santri}/profil/generate-wali-code', [SantriProfileController::class, 'generateWaliCode'])->name('profil.generate_wali_code');
        Route::get('/{santri}/profil/rapor/export', [SantriProfileController::class, 'exportRapor'])->name('profil.rapor.export');
        Route::get('/{santri}/profil/rapor/export-pdf', [SantriProfileController::class, 'exportRaporPdf'])->name('profil.rapor.export.pdf');

        // Portofolio Santri (menggunakan view pengajaran.santri.*)
        Route::get('/portofolio', [SantriProfileController::class, 'listForPortofolio'])->name('portofolio.list');
        Route::get('/{santri}/portofolio', [SantriProfileController::class, 'portofolio'])->name('profil.portofolio');
        Route::get('/{santri}/portofolio/export-pdf', [SantriProfileController::class, 'exportPortofolioPdf'])->name('profil.portofolio.export-pdf');
    });

    // Rute untuk melihat jadwal publik (per kelas/guru)
    Route::get('/jadwal', [\App\Http\Controllers\PublicScheduleController::class, 'index'])->name('jadwal.public.index');
    Route::get('/jadwal/print/guru-libur/{day}', [PublicScheduleController::class, 'printGuruLibur']);
    Route::get('/menu', function () {
        return view('menu');
    })->name('menu.index');
    Route::get('/perizinan/{perizinan}/pdf', [PerizinanController::class, 'generatePdf'])->name('perizinan.pdf');

    // --- GRUP RUTE PENGELOLAAN PENGAJARAN ---
    Route::prefix('pengajaran')->name('pengajaran.')->group(function () {
        Route::resource('kelas', KelasController::class)->except(['show']);
        Route::get('kelas/{kelas}/santri', [SantriController::class, 'index'])->name('santris.index');
        Route::get('kelas/{kelas}/santri/create', [SantriController::class, 'create'])->name('santris.create');
        Route::post('santri', [SantriController::class, 'store'])->name('santris.store');
        Route::get('santri/{santri}/edit', [SantriController::class, 'edit'])->name('santris.edit');
        Route::put('santri/{santri}', [SantriController::class, 'update'])->name('santris.update');
        Route::delete('santri/{santri}', [SantriController::class, 'destroy'])->name('santris.destroy');
        Route::get('kelas/{kelas}/santri-json', [KelasController::class, 'getSantrisJson'])->name('kelas.santris.json');
        Route::resource('mata-pelajaran', MataPelajaranController::class)->except(['show']);
        Route::post('generate-all-wali-codes', [KelasController::class, 'generateAllWaliCodes'])->name('kelas.generate_all_wali_codes');
        Route::get('export-wali-codes', [KelasController::class, 'exportWaliCodes'])->name('kelas.export_wali_codes');
        Route::resource('jabatan', JabatanController::class)->except(['show']);
        Route::post('kelas/{kelas}/assign-jabatan', [KelasController::class, 'assignJabatan'])->name('kelas.assign_jabatan');
        Route::delete('remove-jabatan/{jabatanUser}', [KelasController::class, 'removeJabatan'])->name('kelas.remove_jabatan');
        Route::post('kelas/{kela}/assign-subjects', [KelasController::class, 'assignSubjects'])->name('kelas.assignSubjects');
        Route::resource('absensi', AbsensiController::class)->only(['index', 'store']);
        Route::get('absensi/export', [AbsensiController::class, 'exportLeger'])->name('absensi.export');
        Route::get('absensi/get-schedules-by-kelas/{kelas}', [AbsensiController::class, 'getSchedulesByKelas']);
        Route::put('absensi/{absensi}', [AbsensiController::class, 'update'])->name('absensi.update');
        Route::delete('absensi/{absensi}', [AbsensiController::class, 'destroy'])->name('absensi.destroy');
        Route::get('absensi/laporan-periodik', [AbsensiController::class, 'laporanPeriodik'])->name('absensi.laporan-periodik');
        Route::get('absensi/export-periodik', [AbsensiController::class, 'exportPeriodik'])->name('absensi.export-periodik');
    });

    // --- RUTE UNTUK MANAJEMEN PERIZINAN ---
    Route::prefix('perizinan')->name('perizinan.')->group(function () {
        Route::get('create/{santri}', [PerizinanController::class, 'create'])->name('create');
        Route::post('store', [PerizinanController::class, 'store'])->name('store');
        Route::get('/', [PerizinanController::class, 'index'])->name('index');
        Route::delete('/{perizinan}', [PerizinanController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-delete', [PerizinanController::class, 'bulkDestroy'])->name('bulkDestroy');
        Route::get('/riwayat', [PerizinanController::class, 'riwayat'])->name('riwayat');
    });

    // --- GRUP RUTE KHUSUS ADMIN ---
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);

        // ======================================================================
        // PENYESUAIAN DAN PENAMBAHAN RUTE MASTER DATA
        // ======================================================================
        Route::prefix('master-data')->group(function () {
            // Rute untuk Manajemen Ruangan
            Route::resource('rooms', RoomController::class)
                ->names('rooms')
                ->except('show');

            // --- RUTE BARU UNTUK MANAJEMEN GURU ---
            // Rute untuk CRUD (Tambah manual, lihat, edit, hapus) data guru
            Route::resource('teachers', TeacherController::class)
                ->names('teachers')
                ->except('show');

            // Rute untuk memproses file Excel yang di-upload
            Route::post('teachers/import', [TeacherController::class, 'import'])->name('teachers.import');
            // --- AKHIR RUTE MANAJEMEN GURU ---

            Route::get('/settings', [AppSettingController::class, 'index'])->name('settings.index');
            Route::put('/settings', [AppSettingController::class, 'update'])->name('settings.update');

            // Rute untuk Ketersediaan Guru
            Route::get('teacher-availability', [TeacherAvailabilityController::class, 'index'])->name('teacher-availability.index');
            Route::get('teacher-availability/{teacher}/edit', [TeacherAvailabilityController::class, 'edit'])->name('teacher-availability.edit');
            Route::put('teacher-availability/{teacher}', [TeacherAvailabilityController::class, 'update'])->name('teacher-availability.update');
        });

        // Rute untuk Aturan Penjadwalan
        Route::get('configuration/rules', [RuleController::class, 'index'])->name('rules.index');
        Route::post('configuration/rules', [RuleController::class, 'store'])->name('rules.store');

        // Rute untuk Generator Jadwal
        Route::get('scheduling/generator', [ScheduleGeneratorController::class, 'show'])->name('generator.show');
        Route::post('scheduling/generator', [ScheduleGeneratorController::class, 'generate'])->name('generator.generate');

        // Rute untuk Melihat Jadwal
        Route::get('scheduling/view/grid', [ScheduleViewController::class, 'grid'])->name('schedule.view.grid');

        // RUTE BARU UNTUK FITUR TUKAR JADWAL MANUAL
        Route::get('scheduling/swap', [\App\Http\Controllers\Admin\Scheduling\ScheduleSwapController::class, 'showForm'])->name('schedule.swap.show');
        Route::post('scheduling/swap/process', [\App\Http\Controllers\Admin\Scheduling\ScheduleSwapController::class, 'processSwap'])->name('schedule.swap.process');

        // ======================================================================
        // RUTE UNTUK MANUAL SCHEDULING DENGAN GRID VIEW YANG DITINGKATKAN
        // ======================================================================
        Route::prefix('scheduling/manual')->name('scheduling.manual.')->group(function () {
            // Rute untuk form manual tradisional
            Route::get('/create', [ScheduleManualController::class, 'create'])->name('create');
            Route::post('/store', [ScheduleManualController::class, 'store'])->name('store');
            Route::get('/{schedule}/edit', [ScheduleManualController::class, 'edit'])->name('edit');
            Route::put('/{schedule}/update', [ScheduleManualController::class, 'update'])->name('update');
            Route::delete('/{schedule}/delete', [ScheduleManualController::class, 'destroy'])->name('destroy');
            Route::delete('/clear-class/{kelas_id}', [ScheduleManualController::class, 'clearClass'])->name('clear-class');

            // RUTE BARU UNTUK GRID VIEW YANG DITINGKATKAN
            Route::get('/grid', [ScheduleManualController::class, 'grid'])->name('grid');
            Route::get('/grid-data', [ScheduleManualController::class, 'gridData'])->name('grid.data');
            Route::post('/quick-add', [ScheduleManualController::class, 'quickAdd'])->name('quick.add');
            Route::put('/{schedule}/quick-update', [ScheduleManualController::class, 'quickUpdate'])->name('quick.update');
            Route::post('/bulk-update', [ScheduleManualController::class, 'bulkUpdate'])->name('bulk.update');
            Route::post('/copy-pattern', [ScheduleManualController::class, 'copyPattern'])->name('copy.pattern');
            Route::delete('/quick-delete/{schedule}', [ScheduleManualController::class, 'quickDelete'])->name('quick.delete');
        });

        // Rute untuk Sistem Hybrid (tetap di luar group manual)
        Route::prefix('scheduling')->name('scheduling.')->group(function () {
            // Generator Hybrid
            Route::post('/generator/hybrid', [ScheduleGeneratorController::class, 'generateHybrid'])->name('generator.hybrid');
        });
    });

    // --- RUTE UNTUK MANAJEMEN PELANGGARAN ---
    Route::resource('pelanggaran', PelanggaranController::class)->except(['show']);
    Route::resource('pelanggaran', PelanggaranController::class);
    Route::post('pelanggaran/bulk-destroy', [PelanggaranController::class, 'bulkDestroy'])->name('pelanggaran.bulkDestroy');

    // --- RUTE UNTUK KESEHATAN (RIWAYAT PENYAKIT) ---
    Route::prefix('kesehatan')->name('kesehatan.')->group(function () {
        Route::get('/santri/{santri}/riwayat-penyakit/create', [RiwayatPenyakitController::class, 'create'])->name('riwayat_penyakit.create');
        Route::post('/santri/{santri}/riwayat-penyakit', [RiwayatPenyakitController::class, 'store'])->name('riwayat_penyakit.store');
        Route::get('/riwayat-penyakit/{riwayatPenyakit}/edit', [RiwayatPenyakitController::class, 'edit'])->name('riwayat_penyakit.edit');
        Route::put('/riwayat-penyakit/{riwayatPenyakit}', [RiwayatPenyakitController::class, 'update'])->name('riwayat_penyakit.update');
        Route::delete('/riwayat-penyakit/{riwayatPenyakit}', [RiwayatPenyakitController::class, 'destroy'])->name('riwayat_penyakit.destroy');
    });

    // --- RUTE UNTUK LAPORAN & EXPORT ---
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/perizinan', [ReportController::class, 'perizinan'])->name('perizinan');
        Route::get('/perizinan/export', [ReportController::class, 'exportPerizinan'])->name('perizinan.export');
        Route::get('/pelanggaran', [ReportController::class, 'pelanggaran'])->name('pelanggaran');
        Route::get('/pelanggaran/export', [ReportController::class, 'exportPelanggaran'])->name('pelanggaran.export');
        Route::get('/santri/export', [ReportController::class, 'exportSantri'])->name('santri.export');
    });

    // --- RUTE BARU UNTUK AKADEMIK ---
    Route::prefix('akademik')->name('akademik.')->group(function () {
        Route::get('/nilai', [NilaiController::class, 'index'])->name('nilai.index');
        Route::post('/nilai', [NilaiController::class, 'store'])->name('nilai.store');
        Route::get('/nilai/export', [NilaiController::class, 'exportLeger'])->name('nilai.export');
        Route::get('/placement', [PlacementController::class, 'index'])->name('placement.index');
        Route::post('/placement', [PlacementController::class, 'place'])->name('placement.place');
        Route::get('/nilai/get-mapel-by-kelas/{kelas}', [NilaiController::class, 'getMataPelajaranByKelas'])->name('nilai.get-mapel-by-kelas');
    });

    // --- RUTE BARU UNTUK KEASRAMAAN ---
    Route::prefix('keasramaan')->name('keasramaan.')->group(function () {
        Route::get('/santri/{santri}/catatan/create', [CatatanHarianController::class, 'create'])->name('catatan.create');
        Route::post('/santri/{santri}/catatan', [CatatanHarianController::class, 'store'])->name('catatan.store');
        Route::get('/catatan/{catatan}/edit', [CatatanHarianController::class, 'edit'])->name('catatan.edit');
        Route::put('/catatan/{catatan}', [CatatanHarianController::class, 'update'])->name('catatan.update');
        Route::delete('/catatan/{catatan}', [CatatanHarianController::class, 'destroy'])->name('catatan.destroy');
        Route::get('/santri/{santri}/prestasi/create', [PrestasiController::class, 'create'])->name('prestasi.create');
        Route::post('/santri/{santri}/prestasi', [PrestasiController::class, 'store'])->name('prestasi.store');
        Route::get('/prestasi/{prestasi}/edit', [PrestasiController::class, 'edit'])->name('prestasi.edit');
        Route::put('/prestasi/{prestasi}', [PrestasiController::class, 'update'])->name('prestasi.update');
        Route::delete('/prestasi/{prestasi}', [PrestasiController::class, 'destroy'])->name('prestasi.destroy');
    });

    // --- RUTE BARU UNTUK PUSAT MANAJEMEN SANTRI ---
    Route::prefix('manajemen-santri')->name('admin.santri-management.')->group(function () {
        Route::get('/', [SantriManagementController::class, 'index'])->name('index');
        Route::get('/import', [SantriManagementController::class, 'showImportForm'])->name('import.show');
        Route::post('/import', [SantriManagementController::class, 'import'])->name('import.store');
    });
    Route::get('/jadwal/print/{type}/{id}', [PublicScheduleController::class, 'print'])->name('jadwal.public.print');

    // --- RUTE BARU UNTUK MANAJEMEN INVENTARIS (Scoped per Room) ---
    Route::prefix('admin/rooms/{room}/inventory')->name('admin.rooms.inventory.')->group(function () {
        Route::get('/', [InventoryItemController::class, 'index'])->middleware(['auth', 'verified'])->name('index');
        Route::get('/create', [InventoryItemController::class, 'create'])->middleware(['auth', 'verified'])->name('create');
        Route::post('/', [InventoryItemController::class, 'store'])->middleware(['auth', 'verified'])->name('store');
        Route::get('/{inventoryItem}/edit', [InventoryItemController::class, 'edit'])->middleware(['auth', 'verified'])->name('edit');
        Route::put('/{inventoryItem}', [InventoryItemController::class, 'update'])->middleware(['auth', 'verified'])->name('update');
        Route::delete('/{inventoryItem}', [InventoryItemController::class, 'destroy'])->middleware(['auth', 'verified'])->name('destroy');
    });

    // Rekapan Harian routes
    Route::prefix('pengajaran/rekapan-harian')->name('pengajaran.rekapan-harian.')->group(function () {
        Route::get('/', [RekapanHarianController::class, 'index'])->name('index')->middleware('can:viewAny,App\Models\RekapanHarian');
        Route::post('/', [RekapanHarianController::class, 'store'])->name('store')->middleware('can:create,App\Models\RekapanHarian');
        Route::get('/laporan', [RekapanHarianController::class, 'laporan'])->name('laporan')->middleware('can:viewReport,App\Models\RekapanHarian');
        Route::post('/export', [RekapanHarianController::class, 'export'])->name('export');
    });
});

require __DIR__ . '/auth.php';
