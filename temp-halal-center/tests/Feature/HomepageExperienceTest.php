<?php

namespace Tests\Feature;

use Database\Seeders\DemoContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageExperienceTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_key_public_sections(): void
    {
        $this->seed(DemoContentSeeder::class);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('KDEKS');
        $response->assertSee('Peta Sebaran Halal');
        $response->assertSee('Program Sertifikasi');
        $response->assertSee('Direktori Produk');
        $response->assertSeeText('Dokumen & Regulasi');
        $response->assertSeeText('Artikel & Publikasi');
        $response->assertSeeText('Galeri Dokumentasi');
    }

    public function test_sehati_registration_can_be_submitted(): void
    {
        $this->seed(DemoContentSeeder::class);

        $response = $this->post(route('sehati.store'), [
            'owner_name' => 'Siti Aminah',
            'business_name' => 'Warung Amanah',
            'product_name' => 'Keripik Pisang',
            'phone' => '081234567891',
            'description' => 'Usaha rumahan makanan ringan.',
        ]);

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('status', 'Pendaftaran SEHATI berhasil dikirim.');

        $this->assertDatabaseHas('sehati_registrations', [
            'owner_name' => 'Siti Aminah',
            'business_name' => 'Warung Amanah',
            'product_name' => 'Keripik Pisang',
        ]);
    }

    public function test_sehati_registration_validation_uses_named_error_bag(): void
    {
        $response = $this->from(route('home'))->post(route('sehati.store'), []);

        $response->assertRedirect(route('home'));
        $response->assertSessionHasErrorsIn('sehatiRegistration', [
            'owner_name',
            'business_name',
            'product_name',
            'phone',
        ]);
    }

    public function test_public_content_routes_render_real_detail_pages(): void
    {
        $this->seed(DemoContentSeeder::class);

        $article = \App\Models\Article::query()->where('status', 'published')->firstOrFail();
        $product = \App\Models\HalalProduct::query()->where('status', 'published')->firstOrFail();
        $resource = \App\Models\KnowledgeResource::query()->firstOrFail();
        $regulation = \App\Models\Regulation::query()->firstOrFail();
        $event = \App\Models\Event::query()->where('status', 'published')->firstOrFail();
        $location = \App\Models\HalalLocation::query()->where('status', 'published')->firstOrFail();

        $this->get(route('articles.index'))->assertOk()->assertSeeText('Artikel & Publikasi');
        $this->get(route('articles.show', $article->slug))->assertOk()->assertSeeText($article->title);
        $this->get(route('gallery.index'))->assertOk()->assertSeeText('Galeri Kegiatan');
        $this->get(route('products.index'))->assertOk()->assertSeeText('Produk Halal');
        $this->get(route('products.show', $product->slug))->assertOk()->assertSeeText($product->name);
        $this->get(route('resources.index'))->assertOk()->assertSeeText('Pustaka Dokumen');
        $this->get(route('resources.show', $resource->slug))->assertOk()->assertSeeText($resource->title);
        $this->get(route('regulations.index'))->assertOk()->assertSeeText('Regulasi');
        $this->get(route('regulations.show', $regulation->slug))->assertOk()->assertSeeText($regulation->title);
        $this->get(route('events.index'))->assertOk()->assertSeeText('Agenda Kegiatan');
        $this->get(route('events.show', $event->slug))->assertOk()->assertSeeText($event->title);
        $this->get(route('locations.show', $location->slug))->assertOk()->assertSeeText($location->name);
    }
}
