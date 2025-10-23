<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class AppSettingsSeeder extends Seeder
{
    public function run()
    {
        // Pastikan folder images ada di storage/app/public
        Storage::disk('public')->makeDirectory('images');

        $settings = [
            [
                'key' => 'logo_jadwal_pelajaran',
                'value' => 'images/logo-jadwal-pelajaran.png',
                'type' => 'image',
                'description' => 'Logo untuk jadwal pelajaran'
            ],
            [
                'key' => 'logo_jadwal_mengajar',
                'value' => 'images/logo-mengajar.png',
                'type' => 'image',
                'description' => 'Logo untuk jadwal mengajar guru'
            ],
            [
                'key' => 'logo_surat_izin',
                'value' => 'images/logo-kunka-merah1-min.png',
                'type' => 'image',
                'description' => 'Logo untuk surat izin santri'
            ],
            [
                'key' => 'logo_tanda_tangan',
                'value' => 'images/logo-tanda-tangan.jpg',
                'type' => 'image',
                'description' => 'Logo tanda tangan umum'
            ],
            [
                'key' => 'logo_tanda_tangan_jadwal',
                'value' => 'images/logo-tanda-tangan-jadwal.jpg',
                'type' => 'image',
                'description' => 'Logo tanda tangan khusus untuk dokumen jadwal'
            ],
            [
                'key' => 'logo_tanda_tangan_guru_libur',
                'value' => 'images/logo-tanda-tangan-guru-libur.jpg',
                'type' => 'image',
                'description' => 'Logo tanda tangan khusus untuk dokumen guru libur'
            ],
            [
                'key' => 'logo_tanda_tangan_surat_izin',
                'value' => 'images/logo-tanda-tangan-surat-izin.jpg',
                'type' => 'image',
                'description' => 'Logo tanda tangan khusus untuk surat izin santri'
            ],
            [
                'key' => 'nama_penandatangan',
                'value' => 'Al Ustadz Dzikri Adzkiya Arief, B.A.',
                'type' => 'text',
                'description' => 'Nama penanda tangan umum'
            ],
            [
                'key' => 'jabatan_penandatangan',
                'value' => 'Direktur II',
                'type' => 'text',
                'description' => 'Jabatan penanda tangan umum'
            ],
            [
                'key' => 'nama_penandatangan_jadwal',
                'value' => 'Al Ustadz Ahmad Fauzi, M.Pd.',
                'type' => 'text',
                'description' => 'Nama penanda tangan khusus untuk dokumen jadwal'
            ],
            [
                'key' => 'jabatan_penandatangan_jadwal',
                'value' => 'Kepala Sekolah',
                'type' => 'text',
                'description' => 'Jabatan penanda tangan khusus untuk dokumen jadwal'
            ],
            [
                'key' => 'nama_penandatangan_guru_libur',
                'value' => 'Al Ustadzah Siti Aisyah, S.Ag.',
                'type' => 'text',
                'description' => 'Nama penanda tangan khusus untuk dokumen guru libur'
            ],
            [
                'key' => 'jabatan_penandatangan_guru_libur',
                'value' => 'Wakil Direktur Pengasuhan',
                'type' => 'text',
                'description' => 'Jabatan penanda tangan khusus untuk dokumen guru libur'
            ],
            [
                'key' => 'nama_penandatangan_surat_izin',
                'value' => 'Al Ustadz Muhammad Iqbal, Lc.',
                'type' => 'text',
                'description' => 'Nama penanda tangan khusus untuk surat izin santri'
            ],
            [
                'key' => 'jabatan_penandatangan_surat_izin',
                'value' => 'Bagian Pengasuhan Santri',
                'type' => 'text',
                'description' => 'Jabatan penanda tangan khusus untuk surat izin santri'
            ],
            [
                'key' => 'nama_pondok',
                'value' => 'Pondok Pesantren Kun Karima',
                'type' => 'text',
                'description' => 'Nama lengkap pondok pesantren'
            ],
            [
                'key' => 'alamat_pondok',
                'value' => 'Ciekek Hilir, Kec. Taktakan, Kota Serang, Banten',
                'type' => 'text',
                'description' => 'Alamat lengkap pondok'
            ],
            [
                'key' => 'telepon_pondok',
                'value' => '(0254) 123456',
                'type' => 'text',
                'description' => 'Nomor telepon pondok'
            ]
        ];

        foreach ($settings as $setting) {
            AppSetting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'description' => $setting['description']
                ]
            );
        }
    }
}