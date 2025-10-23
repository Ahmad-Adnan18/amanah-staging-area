<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AppSettingController extends Controller
{
    public function index()
    {
        $settings = AppSetting::all()->groupBy('type');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $changes = [];

        // Validasi input
        $request->validate([
            'logo_jadwal_pelajaran' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'logo_jadwal_mengajar' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'logo_surat_izin' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'logo_tanda_tangan' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'logo_tanda_tangan_jadwal' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'logo_tanda_tangan_guru_libur' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'logo_tanda_tangan_surat_izin' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'nama_penandatangan' => 'nullable|string|max:255',
            'jabatan_penandatangan' => 'nullable|string|max:255',
            'nama_penandatangan_jadwal' => 'nullable|string|max:255',
            'jabatan_penandatangan_jadwal' => 'nullable|string|max:255',
            'nama_penandatangan_guru_libur' => 'nullable|string|max:255',
            'jabatan_penandatangan_guru_libur' => 'nullable|string|max:255',
            'nama_penandatangan_surat_izin' => 'nullable|string|max:255',
            'jabatan_penandatangan_surat_izin' => 'nullable|string|max:255',
            'nama_pondok' => 'nullable|string|max:255',
            'alamat_pondok' => 'nullable|string',
            'telepon_pondok' => 'nullable|string|max:20',
        ]);

        foreach ($request->except('_token', '_method') as $key => $value) {
            $setting = AppSetting::where('key', $key)->first();

            if (!$setting) {
                $type = str_contains($key, 'logo_') ? 'image' : 'text';
                $setting = new AppSetting(['key' => $key, 'type' => $type]);
            }

            // Handle file uploads
            if (str_starts_with($key, 'logo_') && $request->hasFile($key)) {
                // Hapus file lama jika ada
                if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                    Storage::disk('public')->delete($setting->value);
                    Log::info("Deleted old file for {$key}: {$setting->value}");
                }

                $file = $request->file($key);
                $filename = $this->getFixedFilename($key, $file);

                try {
                    // Simpan file
                    $path = $file->storeAs('images', $filename, 'public');
                    $value = $path;
                    $changes[$key] = $path;
                    
                    // DEBUG: Log informasi file
                    Log::info("=== FILE UPLOAD DEBUG ===");
                    Log::info("Key: {$key}");
                    Log::info("Filename: {$filename}");
                    Log::info("Storage Path: {$path}");
                    Log::info("Full Path: " . storage_path('app/public/' . $path));
                    Log::info("Public URL: " . asset('storage/' . $path));
                    Log::info("File exists: " . (Storage::disk('public')->exists($path) ? 'YES' : 'NO'));
                    Log::info("========================");

                } catch (\Exception $e) {
                    Log::error("Failed to upload {$key}: " . $e->getMessage());
                    return redirect()->back()->with('error', "Gagal mengunggah {$key}. Silakan coba lagi.");
                }
            } else {
                $value = $value;
                $changes[$key] = $value;
            }

            // Simpan ke database
            $setting->updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? '', 'type' => $setting->type]
            );
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan berhasil diperbarui');
    }

    private function getFixedFilename($key, $file)
    {
        $baseNames = [
            'logo_jadwal_pelajaran' => 'logo-jadwal-pelajaran',
            'logo_jadwal_mengajar' => 'logo-mengajar',
            'logo_surat_izin' => 'logo-kunka-merah1-min',
            'logo_tanda_tangan' => 'logo-tanda-tangan',
            'logo_tanda_tangan_jadwal' => 'logo-tanda-tangan-jadwal',
            'logo_tanda_tangan_guru_libur' => 'logo-tanda-tangan-guru-libur',
            'logo_tanda_tangan_surat_izin' => 'logo-tanda-tangan-surat-izin',
        ];

        $baseName = $baseNames[$key] ?? 'default-image';
        $extension = $file->getClientOriginalExtension();
        
        return $baseName . '.' . $extension;
    }
}