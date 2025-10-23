<?php

namespace App\Http\Controllers\Admin\Scheduling;

use App\Http\Controllers\Controller;
use App\Services\ScheduleGeneratorService;
use Illuminate\Http\Request;

class ScheduleGeneratorController extends Controller
{
    /**
     * Menampilkan halaman generator.
     */
    public function show()
    {
        return view('admin.scheduling.generator.index');
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
     * Menjalankan proses pembuatan jadwal dalam mode hybrid.
     */
    public function generateHybrid(Request $request, ScheduleGeneratorService $generator)
    {
        $request->validate([
            'clear_existing' => 'boolean',
            'strategy' => 'in:incremental,fill_gaps,replace_conflicts',
        ]);

        $clearExisting = $request->boolean('clear_existing', false);
        $strategy = $request->input('strategy', 'incremental');

        try {
            // Inject parameter ke service
            $result = $generator->run($clearExisting, $strategy);

            if ($result['success']) {
                $message = $clearExisting
                    ? 'Jadwal berhasil dibuat ulang seluruhnya!'
                    : 'Jadwal berhasil ditambahkan ke jadwal existing!';

                return redirect()->route('admin.generator.show')
                    ->with('success', $message)
                    ->with('log', $result['log']);
            } else {
                return redirect()->route('admin.generator.show')
                    ->with('warning', 'Jadwal berhasil dibuat, namun beberapa mata pelajaran tidak dapat ditempatkan.')
                    ->with('unplaced_subjects', $result['unplaced'])
                    ->with('log', $result['log']);
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.generator.show')
                ->with('error', 'Terjadi kesalahan tak terduga: ' . $e->getMessage());
        }
    }
}

