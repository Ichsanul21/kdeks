<?php

namespace Tests\Feature;

use Database\Seeders\DemoContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiHomeExperienceTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_api_returns_expected_sections(): void
    {
        $this->seed(DemoContentSeeder::class);

        $response = $this->getJson('/api/home');

        $response->assertOk();
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'statistics' => [
                    'certificates_total',
                    'products_total',
                    'assistants_total',
                ],
                'regions',
                'lph_partners',
                'potential_items',
                'sector_items',
                'articles',
                'events',
                'resources',
            ],
        ]);
    }
}
