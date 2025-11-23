<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SliderItem;

class SliderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample slider items
        SliderItem::create([
            'title' => 'Kegiatan Belajar Mengajar',
            'type' => 'image',
            'path' => 'slider/kegiatan-belajar.jpg',
            'is_active' => true,
            'order' => 1,
        ]);

        SliderItem::create([
            'title' => 'Kegiatan Ekstrakurikuler',
            'type' => 'image',
            'path' => 'slider/ekstrakurikuler.jpg',
            'is_active' => true,
            'order' => 2,
        ]);

        SliderItem::create([
            'title' => 'Video Pengenalan',
            'type' => 'video',
            'path' => 'slider/video-pengenalan.mp4',
            'is_active' => true,
            'order' => 3,
        ]);
        
        // Add sample external media
        SliderItem::create([
            'title' => 'Video YouTube',
            'type' => 'external',
            'external_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // Contoh video YouTube
            'is_active' => true,
            'order' => 4,
        ]);

        SliderItem::create([
            'title' => 'Instagram Post',
            'type' => 'external',
            'external_url' => 'https://www.instagram.com/p/CKy2ufUgRdO/', // Contoh Instagram post
            'is_active' => true,
            'order' => 5,
        ]);

        // Add more sample items as needed
    }
}
