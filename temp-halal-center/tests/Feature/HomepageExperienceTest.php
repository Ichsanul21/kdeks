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
}
