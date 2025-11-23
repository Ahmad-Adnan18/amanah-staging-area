<?php

namespace Database\Seeders;

use App\Models\KurikulumTemplate;
use Illuminate\Database\Seeder;

class KurikulumTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kurikulumTemplates = [
            ['nama_template' => 'Kurikulum 2013'],
            ['nama_template' => 'Kurikulum Merdeka'],
            ['nama_template' => 'Kurikulum Pondok Pesantren'],
        ];

        foreach ($kurikulumTemplates as $template) {
            KurikulumTemplate::create($template);
        }

        KurikulumTemplate::factory(5)->create();
    }
}