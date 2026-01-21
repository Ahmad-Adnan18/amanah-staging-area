<?php

namespace App\Http\Controllers\Admin\Scheduling;

use App\Http\Controllers\Controller;
use App\Services\ScheduleGeneratorService;
use Illuminate\Http\Request;
use App\Models\AppSetting;

class ScheduleGeneratorController extends Controller
{
    /**
     * Menampilkan halaman generator.
     */
    public function show()
    {
        $hasPassword = !empty(AppSetting::getValue('generator_password'));
        return view('admin.scheduling.generator.index', compact('hasPassword'));
    }

    /**
     * Menjalankan proses pembuatan jadwal.
     */
    public function generate(ScheduleGeneratorService $generator)
    {
        try {
            // [PERBAIKAN] Menangkap hasil yang lebih detail dari service
            $result = $generator->run();
            $unplaced = $result['unplaced'];
            $log = $result['log'];

            if ($result['success']) {
                return redirect()->route('admin.generator.show')
                    ->with('success', 'Jadwal berhasil dibuat seluruhnya!')
                    ->with('log', $log);
            } else {
                return redirect()->route('admin.generator.show')
                    ->with('warning', 'Jadwal berhasil dibuat, namun beberapa mata pelajaran tidak dapat ditempatkan.')
                    ->with('unplaced_subjects', $unplaced)
                    ->with('log', $log);
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.generator.show')
                ->with('error', 'Terjadi kesalahan tak terduga: ' . $e->getMessage());
        }
    }

    /**
     * Menjalankan proses pembuatan jadwal (Unified Generator)
     */
    public function generateHybrid(Request $request, ScheduleGeneratorService $generator)
    {
        // Validasi Password
        $savedPassword = AppSetting::getValue('generator_password');
        if (!empty($savedPassword)) {
            $inputPassword = $request->input('password');
            if ($inputPassword !== $savedPassword) {
                return redirect()->back()->with('error', 'Password generator salah! Akses ditolak.');
            }
        }

        $clearExisting = $request->boolean('clear_existing', true);

        try {
            $result = $generator->run($clearExisting, 'incremental');

            $sessionData = [
                'log' => $result['log'] ?? [],
                'capacity_warnings' => $result['capacity_warnings'] ?? [],
                'forced_placements' => $result['forced_placements'] ?? [],
            ];

            if ($result['success']) {
                $message = $clearExisting
                    ? 'Jadwal berhasil dibuat ulang seluruhnya!'
                    : 'Jadwal berhasil ditambahkan ke jadwal existing!';

                return redirect()->route('admin.generator.show')
                    ->with('success', $message)
                    ->with($sessionData);
            } else {
                return redirect()->route('admin.generator.show')
                    ->with('warning', 'Jadwal berhasil dibuat, namun ' . count($result['unplaced']) . ' mata pelajaran tidak dapat ditempatkan.')
                    ->with('unplaced_subjects', $result['unplaced'])
                    ->with($sessionData);
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.generator.show')
                ->with('error', 'Terjadi kesalahan tak terduga: ' . $e->getMessage());
        }
    }
}

