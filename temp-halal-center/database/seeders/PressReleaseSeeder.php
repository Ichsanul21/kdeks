<?php

namespace Database\Seeders;

use App\Models\PressRelease;
use Illuminate\Database\Seeder;

class PressReleaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PressRelease::create([
            'title' => 'Livestream: Peluncuran Halal Center Kaltim',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // Placeholder
            'description' => 'Siaran langsung peluncuran Halal Center Kalimantan Timur bersama para pemangku kepentingan.',
            'is_featured' => true,
            'status' => 'streaming',
        ]);

        PressRelease::create([
            'title' => 'Workshop Sertifikasi Halal UMKM 2026',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'description' => 'Dokumentasi workshop akselerasi sertifikasi halal untuk pelaku UMKM di Samarinda.',
            'is_featured' => false,
            'status' => 'archived',
        ]);

        PressRelease::create([
            'title' => 'Liputan Media: Ekosistem Ekonomi Syariah',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'description' => 'Wawancara eksklusif mengenai perkembangan ekosistem ekonomi syariah di Kalimantan Timur.',
            'is_featured' => false,
            'status' => 'archived',
        ]);
    }
}
