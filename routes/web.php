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
use App\Http\Controllers\Keasramaan\CatatanHarianController;
use App\Http\Controllers\Keasramaan\PrestasiController;
use App\Http\Controllers\Akademik\PlacementController;
use App\Http\Controllers\Admin\SantriManagementController;
use App\Http\Controllers\Akademik\KurikulumController;
use App\Http\Controllers\Admin\MasterData\RoomController;
use App\Http\Controllers\Admin\MasterData\TeacherAvailabilityController;
use App\Http\Controllers\Admin\Configuration\RuleController;
use App\Http\Controllers\Admin\Scheduling\ScheduleGeneratorController;
use App\Http\Controllers\Admin\Scheduling\ScheduleViewController;
use App\Http\Controllers\PublicScheduleController;



Route::get('/', function () {
    return redirect()->route('login');
});

// RUTE BARU UNTUK REGISTRASI WALI
Route::get('/wali-register', [WaliRegistrationController::class, 'create'])->name('wali.register');
Route::post('/wali-register', [WaliRegistrationController::class, 'store']);

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // RUTE BARU UNTUK PROFIL SANTRI
    Route::get('/santri/{santri}/profil', [SantriProfileController::class, 'show'])->name('santri.profil.show');
    Route::post('/santri/{santri}/profil/generate-wali-code', [SantriProfileController::class, 'generateWaliCode'])->name('santri.profil.generate_wali_code');
    Route::get('/santri/{santri}/profil/rapor/export', [SantriProfileController::class, 'exportRapor'])->name('santri.profil.rapor.export');
    // RUTE BARU UNTUK EXPORT PDF
    Route::get('/santri/{santri}/profil/rapor/export-pdf', [SantriProfileController::class, 'exportRaporPdf'])->name('santri.profil.rapor.export.pdf');
    // Rute untuk melihat jadwal publik (per kelas/guru)
    Route::get('/jadwal', [\App\Http\Controllers\PublicScheduleController::class, 'index'])->name('jadwal.public.index');

    // --- GRUP RUTE PENGELOLAAN PENGAJARAN ---
    Route::prefix('pengajaran')->name('pengajaran.')->group(function () {
        // ... (semua route pengajaran Anda tetap di sini) ...
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
    });

    // --- RUTE UNTUK MANAJEMEN PERIZINAN ---
    Route::prefix('perizinan')->name('perizinan.')->group(function () {
        // ... (semua route perizinan Anda tetap di sini) ...
        Route::get('create/{santri}', [PerizinanController::class, 'create'])->name('create');
        Route::post('store', [PerizinanController::class, 'store'])->name('store');
        Route::get('/', [PerizinanController::class, 'index'])->name('index');
        Route::delete('/{perizinan}', [PerizinanController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-delete', [PerizinanController::class, 'bulkDestroy'])->name('bulkDestroy');
    });

    // --- GRUP RUTE KHUSUS ADMIN ---
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);

        // == PINDAHKAN KE SINI ==
        // Rute untuk Manajemen Ruangan sekarang berada di dalam grup admin
        Route::resource('master-data/rooms', \App\Http\Controllers\Admin\MasterData\RoomController::class)
            ->names('rooms') // Ini akan menghasilkan nama seperti admin.rooms.index
            ->except('show');
        // Rute untuk Ketersediaan Guru
        Route::get('master-data/teacher-availability', [\App\Http\Controllers\Admin\MasterData\TeacherAvailabilityController::class, 'index'])->name('teacher-availability.index');
        Route::get('master-data/teacher-availability/{teacher}/edit', [\App\Http\Controllers\Admin\MasterData\TeacherAvailabilityController::class, 'edit'])->name('teacher-availability.edit');
        Route::put('master-data/teacher-availability/{teacher}', [\App\Http\Controllers\Admin\MasterData\TeacherAvailabilityController::class, 'update'])->name('teacher-availability.update');
        // Rute untuk Aturan Penjadwalan
        Route::get('configuration/rules', [\App\Http\Controllers\Admin\Configuration\RuleController::class, 'index'])->name('rules.index');
        Route::post('configuration/rules', [\App\Http\Controllers\Admin\Configuration\RuleController::class, 'store'])->name('rules.store');
        // Rute untuk Generator Jadwal
        Route::get('scheduling/generator', [\App\Http\Controllers\Admin\Scheduling\ScheduleGeneratorController::class, 'show'])->name('generator.show');
        Route::post('scheduling/generator', [\App\Http\Controllers\Admin\Scheduling\ScheduleGeneratorController::class, 'generate'])->name('generator.generate');
        // Rute untuk Melihat Jadwal
        Route::get('scheduling/view/grid', [\App\Http\Controllers\Admin\Scheduling\ScheduleViewController::class, 'grid'])->name('schedule.view.grid');
        // RUTE BARU UNTUK FITUR TUKAR JADWAL MANUAL
        Route::get('scheduling/swap', [\App\Http\Controllers\Admin\Scheduling\ScheduleSwapController::class, 'showForm'])->name('schedule.swap.show');
        Route::post('scheduling/swap/process', [\App\Http\Controllers\Admin\Scheduling\ScheduleSwapController::class, 'processSwap'])->name('schedule.swap.process');
    });

    // --- RUTE UNTUK MANAJEMEN PELANGGARAN ---
    Route::resource('pelanggaran', PelanggaranController::class)->except(['show']);

    // --- RUTE UNTUK LAPORAN & EXPORT ---
    Route::prefix('laporan')->name('laporan.')->group(function () {
        // ... (semua route laporan Anda tetap di sini) ...
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
        Route::get('/kurikulum', [KurikulumController::class, 'index'])->name('kurikulum.index');
        Route::post('/kurikulum/template', [KurikulumController::class, 'storeTemplate'])->name('kurikulum.template.store');
        Route::get('/kurikulum/template/{template}/edit', [KurikulumController::class, 'editTemplate'])->name('kurikulum.template.edit');
        Route::put('/kurikulum/template/{template}', [KurikulumController::class, 'updateTemplate'])->name('kurikulum.template.update');
        Route::delete('/kurikulum/template/{template}', [KurikulumController::class, 'destroyTemplate'])->name('kurikulum.template.destroy');
        Route::post('/kurikulum/apply-template', [KurikulumController::class, 'applyTemplate'])->name('kurikulum.apply');
        Route::get('/kurikulum/{kelas}/mapel-json', [KurikulumController::class, 'getMapelJson'])->name('kurikulum.mapel.json');
        Route::post('/kurikulum', [KurikulumController::class, 'store'])->name('kurikulum.store');

        // <-- KODE SEBELUMNYA ADA DI SINI, SEKARANG SUDAH DIPINDAH
    });

    // --- RUTE BARU UNTUK KEASRAMAAN ---
    Route::prefix('keasramaan')->name('keasramaan.')->group(function () {
        // ... (semua route keasramaan Anda tetap di sini) ...
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
});

require __DIR__ . '/auth.php';
