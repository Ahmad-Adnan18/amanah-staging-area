<?php

namespace Database\Seeders;

use App\Models\RiwayatPenyakit;
use Illuminate\Database\Seeder;

class RiwayatPenyakitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $riwayatPenyakits = [
            [
                'santri_id' => 1,
                'nama_penyakit' => 'Demam Berdarah',
                'keterangan' => 'Dirawat di rumah sakit selama 5 hari',
                'tanggal_diagnosis' => '2025-08-15',
                'status' => 'Sembuh',
                'penanganan' => 'Istirahat total dan minum obat teratur',
                'dicatat_oleh' => 1,
            ],
            [
                'santri_id' => 2,
                'nama_penyakit' => 'Alergi makanan',
                'keterangan' => 'Alergi terhadap makanan laut',
                'tanggal_diagnosis' => '2025-09-10',
                'status' => 'Kronis',
                'penanganan' => 'Hindari makanan pemicu alergi',
                'dicatat_oleh' => 1,
            ],
            [
                'santri_id' => 1,
                'nama_penyakit' => 'Flu Biasa',
                'keterangan' => 'Gejala ringan',
                'tanggal_diagnosis' => '2025-10-18',
                'status' => 'Sembuh',
                'penanganan' => 'Istirahat dan minum obat',
                'dicatat_oleh' => 2,
            ],
        ];

        foreach ($riwayatPenyakits as $riwayat) {
            RiwayatPenyakit::create($riwayat);
        }

        RiwayatPenyakit::factory(15)->create();
    }
}