<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\CertificationPath;
use App\Models\Event;
use App\Models\FrequentlyAskedQuestion;
use App\Models\GalleryItem;
use App\Models\HalalLocation;
use App\Models\HalalProduct;
use App\Models\KnowledgeResource;
use App\Models\LphPartner;
use App\Models\Mentor;
use App\Models\OrganizationMember;
use App\Models\PotentialItem;
use App\Models\ProgramSlide;
use App\Models\Region;
use App\Models\Regulation;
use App\Models\SectorItem;
use App\Models\SehatiRegistration;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $editorRole = Role::firstOrCreate(['name' => 'editor']);
        $developerRole = Role::firstOrCreate(['name' => 'developer']);

        $admin = User::updateOrCreate(
            ['email' => 'admin@halalcenter.test'],
            ['name' => 'Halal Center Admin', 'password' => Hash::make('password')]
        );
        $admin->syncRoles([$adminRole]);

        User::updateOrCreate(
            ['email' => 'editor@halalcenter.test'],
            ['name' => 'Halal Center Editor', 'password' => Hash::make('password')]
        )->syncRoles([$editorRole]);

        User::updateOrCreate(
            ['email' => 'developer@halalcenter.test'],
            ['name' => 'Halal Center Developer', 'password' => Hash::make('password')]
        )->syncRoles([$developerRole]);

        SiteSetting::updateOrCreate(['id' => 1], [
            'institution_name' => 'KDEKS Kalimantan Timur',
            'tagline' => 'Komite Daerah Ekonomi Syariah Kaltim yang transparan, maju, dan mudah diakses masyarakat.',
            'hero_badge' => 'Portal Informasi Resmi',
            'short_description' => 'Portal resmi Komite Daerah Ekonomi dan Keuangan Syariah Provinsi Kalimantan Timur untuk layanan sertifikasi halal, direktori produk, dokumen, dan WebGIS ekosistem syariah.',
            'contact_email' => 'halo@halalkaltim.id',
            'contact_phone' => '+62 541 000 123',
            'whatsapp_number' => '6281234567890',
            'address' => 'Jl. Gajah Mada No.2, Samarinda, Kalimantan Timur',
            'consultation_url' => 'https://wa.me/6281234567890',
            'sehati_url' => 'https://sehati.halal.go.id',
            'logo_path' => 'branding/logo.png',
            'og_image_path' => 'branding/logo.png',
            'meta_title' => 'KDEKS Kalimantan Timur',
            'meta_description' => 'Portal resmi KDEKS Kalimantan Timur untuk sertifikasi halal, peta sebaran usaha halal, dokumen, dan pengembangan ekonomi syariah.',
            'meta_keywords' => 'KDEKS Kaltim, sertifikasi halal, ekonomi syariah, webgis halal, SEHATI',
            'default_locale' => 'id',
            'watermark_enabled' => false,
            'watermark_text' => 'UNPAID PREVIEW',
            'watermark_opacity' => 0.18,
        ]);

        $regions = collect([
            ['name' => 'Samarinda', 'latitude' => -0.502106, 'longitude' => 117.153709, 'halal_msmes_count' => 142, 'mentor_count' => 18, 'issued_certificate_count' => 96, 'service_office_count' => 4, 'sort_order' => 1],
            ['name' => 'Balikpapan', 'latitude' => -1.237928, 'longitude' => 116.852852, 'halal_msmes_count' => 121, 'mentor_count' => 14, 'issued_certificate_count' => 82, 'service_office_count' => 3, 'sort_order' => 2],
            ['name' => 'Bontang', 'latitude' => 0.132426, 'longitude' => 117.485394, 'halal_msmes_count' => 64, 'mentor_count' => 9, 'issued_certificate_count' => 47, 'service_office_count' => 2, 'sort_order' => 3],
            ['name' => 'Kutai Kartanegara', 'latitude' => -0.439055, 'longitude' => 116.981415, 'halal_msmes_count' => 108, 'mentor_count' => 12, 'issued_certificate_count' => 70, 'service_office_count' => 3, 'sort_order' => 4],
        ])->map(function ($region) {
            $region['slug'] = Str::slug($region['name']);

            return Region::updateOrCreate(['name' => $region['name']], $region);
        });

        $lphPartners = collect([
            ['name' => 'LP3H MUI Kaltim', 'partner_type' => 'lp3h', 'contact_person' => 'Sekretariat MUI', 'phone' => '0812-1100-0001', 'email' => 'lp3hmui@halalkaltim.id', 'address' => 'Samarinda', 'description' => 'Pendampingan sertifikasi halal Self Declare untuk pelaku UMK.', 'sort_order' => 1],
            ['name' => 'LP3H Mathlaul Anwar', 'partner_type' => 'lp3h', 'contact_person' => 'Tim Pendamping', 'phone' => '0812-1100-0002', 'email' => 'mathlaul@halalkaltim.id', 'address' => 'Balikpapan', 'description' => 'Fasilitasi pembinaan dokumen dan proses pengajuan SEHATI.', 'sort_order' => 2],
            ['name' => 'LPH Unmul', 'partner_type' => 'lph', 'contact_person' => 'Admin LPH', 'phone' => '0812-1100-0003', 'email' => 'lphunmul@halalkaltim.id', 'address' => 'Samarinda', 'description' => 'Lembaga pemeriksa halal untuk jalur reguler dan pembinaan teknis.', 'sort_order' => 3],
        ])->map(function (array $partner) {
            $partner['slug'] = Str::slug($partner['name']);

            return LphPartner::updateOrCreate(['name' => $partner['name']], $partner + ['is_active' => true]);
        });

        foreach ([
            ['title' => 'Program Sertifikasi Halal Gratis SEHATI', 'subtitle' => 'Layanan Prioritas 2026', 'description' => 'Fasilitasi pengajuan sertifikasi halal gratis bagi UMK melalui skema Self Declare dengan dukungan LP3H di Kalimantan Timur.', 'status' => 'published', 'sort_order' => 1],
            ['title' => 'Pemetaan Digital Ekosistem Halal Kaltim', 'subtitle' => 'WebGIS Interaktif', 'description' => 'Jelajahi sebaran titik usaha halal, rumah potong, unit usaha pesantren, serta layanan pendampingan berdasarkan kota, kategori, dan LP3H.', 'status' => 'published', 'sort_order' => 2],
        ] as $slide) {
            ProgramSlide::updateOrCreate(['title' => $slide['title']], $slide + ['published_at' => now()]);
        }

        foreach ([
            ['title' => 'Data Sertifikat Halal', 'icon_key' => 'badge-check', 'accent_color' => 'emerald', 'summary' => 'Ikhtisar jumlah dan pertumbuhan sertifikat halal terbit di Kalimantan Timur.', 'sort_order' => 1],
            ['title' => 'Usaha Pondok Pesantren', 'icon_key' => 'school', 'accent_color' => 'blue', 'summary' => 'Pemetaan unit usaha pesantren yang masuk ekosistem syariah daerah.', 'sort_order' => 2],
            ['title' => 'Lembaga Amil Zakat', 'icon_key' => 'heart-handshake', 'accent_color' => 'emerald', 'summary' => 'Data lembaga sosial keagamaan yang menopang ekonomi syariah.', 'sort_order' => 3],
            ['title' => 'Lembaga & Komunitas Muslim', 'icon_key' => 'users', 'accent_color' => 'cyan', 'summary' => 'Jaringan komunitas dan lembaga yang aktif mendukung literasi syariah.', 'sort_order' => 4],
            ['title' => 'Data Usaha Travel Umroh', 'icon_key' => 'plane', 'accent_color' => 'amber', 'summary' => 'Rujukan potensi sektor perjalanan religius yang patuh regulasi.', 'sort_order' => 5],
        ] as $item) {
            $item['slug'] = Str::slug($item['title']);
            PotentialItem::updateOrCreate(['title' => $item['title']], $item + ['is_active' => true]);
        }

        foreach ([
            ['title' => 'Keuangan Syariah', 'icon_key' => 'landmark', 'summary' => 'Perbankan, asuransi, dan layanan pembiayaan yang berlandaskan prinsip syariah.', 'sort_order' => 1],
            ['title' => 'Keuangan Sosial Syariah', 'icon_key' => 'coins', 'summary' => 'Penguatan zakat, infaq, sedekah, dan wakaf untuk pembangunan yang inklusif.', 'sort_order' => 2],
            ['title' => 'Bisnis dan Kewirausahaan Syariah', 'icon_key' => 'store', 'summary' => 'Pembinaan usaha dan penguatan wirausaha halal yang kompetitif.', 'sort_order' => 3],
            ['title' => 'Infrastruktur Ekosistem Syariah', 'icon_key' => 'network', 'summary' => 'Arah pengembangan riset, kawasan, teknologi, dan jejaring kelembagaan halal.', 'sort_order' => 4],
        ] as $item) {
            $item['slug'] = Str::slug($item['title']);
            SectorItem::updateOrCreate(['title' => $item['title']], $item + ['is_active' => true]);
        }

        foreach ([
            ['name' => 'Jalur Self Declare', 'path_type' => 'self_declare', 'summary' => 'Untuk UMKM berisiko rendah dengan bahan sederhana.', 'content' => '<p>Persiapan dokumen, pendampingan PPH, verifikasi, dan pengajuan melalui sistem Sehati.</p>', 'sort_order' => 1, 'is_featured' => true],
            ['name' => 'Jalur Reguler', 'path_type' => 'regular', 'summary' => 'Untuk usaha dengan proses dan rantai pasok lebih kompleks.', 'content' => '<p>Mencakup audit menyeluruh, verifikasi dokumen, dan pendampingan intensif.</p>', 'sort_order' => 2, 'is_featured' => true],
        ] as $path) {
            $path['slug'] = Str::slug($path['name']);
            CertificationPath::updateOrCreate(['name' => $path['name']], $path);
        }

        foreach ([
            ['name' => 'Dr. Aisyah Rahman', 'role_title' => 'Ketua Dewan Pakar', 'bio' => '<p>Mengarahkan strategi penguatan halal value chain dan tata kelola lembaga.</p>', 'sort_order' => 1, 'is_board_member' => true],
            ['name' => 'Muhammad Fikri', 'role_title' => 'Kepala Pusat Layanan', 'bio' => '<p>Mengelola orkestrasi sertifikasi halal, layanan bantuan, dan integrasi data UMKM.</p>', 'sort_order' => 2, 'is_board_member' => false],
        ] as $member) {
            OrganizationMember::updateOrCreate(['name' => $member['name']], $member);
        }

        foreach ($regions as $region) {
            Mentor::updateOrCreate(['name' => 'Pendamping '.$region->name], [
                'region_id' => $region->id,
                'slug' => Str::slug('Pendamping '.$region->name),
                'phone' => '0812-000-'.$region->id.'00',
                'email' => strtolower(str_replace(' ', '', $region->name)).'@halalkaltim.id',
                'expertise' => 'SJPH, pendampingan sertifikasi, audit dokumen',
                'district_coverage' => $region->name,
                'bio' => '<p>Pendamping resmi untuk membantu proses sertifikasi halal UMKM lokal.</p>',
                'is_active' => true,
            ]);

            HalalProduct::updateOrCreate(['name' => 'Produk Unggulan '.$region->name], [
                'region_id' => $region->id,
                'slug' => Str::slug('Produk Unggulan '.$region->name),
                'brand_name' => 'Brand '.$region->name,
                'category' => 'Produk Olahan',
                'certificate_number' => 'HC-'.$region->id.'-2026',
                'certificate_issued_at' => now()->subMonths(2),
                'certificate_expires_at' => now()->addYears(4),
                'description' => 'Produk lokal unggulan dengan sertifikasi halal aktif.',
                'status' => 'published',
                'is_featured' => true,
            ]);

            HalalLocation::updateOrCreate(['name' => 'Titik Halal '.$region->name], [
                'region_id' => $region->id,
                'lph_partner_id' => $lphPartners->random()->id,
                'slug' => Str::slug('Titik Halal '.$region->name),
                'location_type' => 'umkm',
                'category' => collect(['Makanan', 'Minuman', 'Wisata Ramah', 'Unit Usaha Ponpes', 'Produk Halal Lainnya', 'Rumah Potong', 'Industri Kreatif', 'Perbankan Syariah', 'Lembaga Keuangan'])->random(),
                'city_name' => $region->name,
                'business_scale' => 'UMKM Binaan',
                'owner_name' => 'Pemilik '.$region->name,
                'brand_name' => 'Brand '.$region->name,
                'product_name' => 'Produk Sertifikasi '.$region->name,
                'address' => 'Sentra UMKM '.$region->name,
                'latitude' => $region->latitude,
                'longitude' => $region->longitude,
                'description' => 'Lokasi UMKM halal yang aktif dalam program pembinaan dan sertifikasi.',
                'certificate_number' => 'MAP-'.$region->id.'-2026',
                'status' => 'published',
                'is_featured' => true,
            ]);
        }

        foreach ([
            ['city' => 'Samarinda', 'name' => 'Kripik Singkong Sari Rasa', 'category' => 'Makanan', 'lat' => -0.485, 'lng' => 117.145, 'brand' => 'Sari Rasa', 'product' => 'Kripik Singkong'],
            ['city' => 'Samarinda', 'name' => 'Rumah Makan Selera Etam', 'category' => 'Minuman', 'lat' => -0.520, 'lng' => 117.160, 'brand' => 'Selera Etam', 'product' => 'Paket Kuliner Halal'],
            ['city' => 'Balikpapan', 'name' => 'Sirup Jeruk Borneo', 'category' => 'Minuman', 'lat' => -1.250, 'lng' => 116.820, 'brand' => 'Jeruk Borneo', 'product' => 'Sirup Jeruk'],
            ['city' => 'Kutai Kartanegara', 'name' => 'RPH Amanah Tenggarong', 'category' => 'Rumah Potong', 'lat' => -0.410, 'lng' => 116.975, 'brand' => 'Amanah', 'product' => 'Layanan Rumah Potong'],
            ['city' => 'Bontang', 'name' => 'Abon Ikan Bontang', 'category' => 'Produk Halal Lainnya', 'lat' => 0.145, 'lng' => 117.460, 'brand' => 'Abon Bontang', 'product' => 'Abon Ikan'],
            ['city' => 'Balikpapan', 'name' => 'Wisata Pantai Ramah Muslim', 'category' => 'Wisata Ramah', 'lat' => -1.273, 'lng' => 116.845, 'brand' => 'Wisata Halal Borneo', 'product' => 'Paket Wisata'],
        ] as $location) {
            $region = $regions->firstWhere('name', $location['city']);

            HalalLocation::updateOrCreate(['name' => $location['name']], [
                'region_id' => $region?->id,
                'lph_partner_id' => $lphPartners->random()->id,
                'slug' => Str::slug($location['name']),
                'location_type' => 'umkm',
                'category' => $location['category'],
                'city_name' => $location['city'],
                'business_scale' => 'Usaha Tersertifikasi',
                'owner_name' => 'Pemilik '.$location['brand'],
                'brand_name' => $location['brand'],
                'product_name' => $location['product'],
                'address' => 'Alamat usaha '.$location['city'],
                'latitude' => $location['lat'],
                'longitude' => $location['lng'],
                'description' => 'Contoh titik usaha halal aktif yang ditampilkan pada peta publik.',
                'certificate_number' => 'ID64'.rand(1000000000, 9999999999),
                'certificate_issued_at' => now()->subMonths(rand(1, 8)),
                'certificate_expires_at' => now()->addYears(4),
                'status' => 'published',
                'is_featured' => true,
            ]);
        }

        foreach ([
            ['type' => 'news', 'title' => 'Pelatihan Penyelia Halal Angkatan Pertama 2026', 'excerpt' => 'Penguatan kapasitas SDM halal daerah untuk mendukung percepatan sertifikasi.', 'body' => '<p>Program pelatihan ini mempertemukan UMKM, penyelia, dan pendamping untuk membangun standardisasi proses halal.</p>', 'author_name' => 'Tim Redaksi', 'status' => 'published', 'is_featured' => true, 'published_at' => now()],
            ['type' => 'research', 'title' => 'Kajian Potensi Pasar Halal Kalimantan Timur', 'excerpt' => 'Laporan riset awal mengenai peluang ekspansi industri halal regional.', 'body' => '<p>Riset ini memetakan peluang sektor kuliner, fashion, serta produk olahan berbasis bahan baku lokal.</p>', 'author_name' => 'Divisi Riset', 'status' => 'published', 'is_featured' => true, 'published_at' => now()],
        ] as $article) {
            $article['slug'] = Str::slug($article['title']);
            Article::updateOrCreate(['title' => $article['title']], $article);
        }

        foreach ([
            ['type' => 'module', 'title' => 'Buku Saku SJPH v2', 'summary' => 'Panduan ringkas penerapan sistem jaminan produk halal bagi UMKM.', 'content' => '<p>Materi pengantar, checklist, dan alur implementasi SJPH.</p>', 'is_featured' => true, 'published_at' => now()],
            ['type' => 'ebook', 'title' => 'Panduan Self Declare', 'summary' => 'Rujukan teknis pendaftaran sertifikasi halal gratis melalui skema SEHATI.', 'content' => '<p>Berisi langkah administrasi, persyaratan, dan alur pendampingan.</p>', 'is_featured' => true, 'published_at' => now()],
        ] as $resource) {
            $resource['slug'] = Str::slug($resource['title']);
            KnowledgeResource::updateOrCreate(['title' => $resource['title']], $resource);
        }

        foreach ([
            ['title' => 'UU Jaminan Produk Halal', 'regulation_type' => 'Undang-Undang', 'regulation_number' => 'UU No. 33 Tahun 2014', 'issued_at' => now()->subYears(10), 'summary' => 'Landasan hukum nasional penyelenggaraan jaminan produk halal.', 'is_featured' => true],
            ['title' => 'Pergub Pengembangan Ekonomi Syariah Daerah', 'regulation_type' => 'Peraturan Gubernur', 'regulation_number' => 'Pergub No. 12 Tahun 2025', 'issued_at' => now()->subYear(), 'summary' => 'Arah kebijakan regional untuk penguatan industri dan ekosistem halal.', 'is_featured' => true],
        ] as $regulation) {
            $regulation['slug'] = Str::slug($regulation['title']);
            Regulation::updateOrCreate(['title' => $regulation['title']], $regulation);
        }

        foreach ([
            ['title' => 'Webinar Roadmap Industri Halal Kaltim', 'summary' => 'Diskusi strategi regional bersama pelaku UMKM dan investor.', 'description' => '<p>Forum ini memaparkan roadmap, peluang pasar, dan kesiapan rantai pasok halal.</p>', 'starts_at' => now()->addDays(10), 'location_name' => 'Samarinda', 'status' => 'published', 'is_featured' => true],
            ['title' => 'Halal Expo MSME Showcase', 'summary' => 'Pameran produk unggulan halal Kalimantan Timur.', 'description' => '<p>Pameran ini mempertemukan produsen, pendamping, investor, dan calon buyer.</p>', 'starts_at' => now()->addDays(20), 'location_name' => 'Balikpapan', 'status' => 'published', 'is_featured' => true],
        ] as $event) {
            $event['slug'] = Str::slug($event['title']);
            Event::updateOrCreate(['title' => $event['title']], $event);
        }

        foreach ([
            ['title' => 'Peluncuran Dashboard Halal', 'media_type' => 'image', 'caption' => 'Peresmian platform data halal regional.', 'is_featured' => true, 'recorded_at' => now()->subDays(3)],
            ['title' => 'Workshop Dokumen Sertifikasi', 'media_type' => 'image', 'caption' => 'Sesi teknis penguatan kesiapan dokumen UMKM.', 'is_featured' => true, 'recorded_at' => now()->subDays(7)],
        ] as $galleryItem) {
            GalleryItem::updateOrCreate(['title' => $galleryItem['title']], $galleryItem);
        }

        foreach ([
            ['question' => 'Bagaimana memulai sertifikasi halal?', 'answer' => '<p>Mulailah dengan memilih jalur sertifikasi yang sesuai, menyiapkan data usaha, lalu hubungi pendamping PPH resmi.</p>', 'sort_order' => 1, 'is_featured' => true],
            ['question' => 'Apakah UMKM bisa mendapatkan pendampingan gratis?', 'answer' => '<p>Ya, program prioritas dan pendampingan tertentu tersedia sesuai kuota dan kebijakan yang berlaku.</p>', 'sort_order' => 2, 'is_featured' => true],
        ] as $faq) {
            FrequentlyAskedQuestion::updateOrCreate(['question' => $faq['question']], $faq);
        }

        foreach ([
            ['lph_partner_id' => $lphPartners[0]->id ?? null, 'owner_name' => 'Ahmad Fauzi', 'business_name' => 'Kedai Mubarok', 'product_name' => 'Sambal Kemasan', 'phone' => '081234567890', 'description' => 'Usaha makanan rumahan siap diajukan.', 'status' => 'new'],
            ['lph_partner_id' => $lphPartners[1]->id ?? null, 'owner_name' => 'Nur Aini', 'business_name' => 'Dapur Borneo', 'product_name' => 'Kue Kering', 'phone' => '081298765432', 'description' => 'Membutuhkan pendampingan dokumen bahan baku.', 'status' => 'reviewed'],
        ] as $registration) {
            SehatiRegistration::updateOrCreate(
                ['owner_name' => $registration['owner_name'], 'business_name' => $registration['business_name']],
                $registration
            );
        }
    }
}
