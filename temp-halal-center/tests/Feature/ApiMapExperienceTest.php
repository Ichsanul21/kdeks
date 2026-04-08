<?php

namespace Tests\Feature;

use App\Models\HalalLocation;
use App\Models\LphPartner;
use Database\Seeders\DemoContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiMapExperienceTest extends TestCase
{
    use RefreshDatabase;

    public function test_map_api_returns_standard_payload(): void
    {
        $this->seed(DemoContentSeeder::class);

        $response = $this->getJson('/api/map');

        $response->assertOk();
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'regions',
                'lph_partners',
                'locations',
            ],
        ]);
    }

    public function test_map_api_can_filter_by_category_and_partner(): void
    {
        $this->seed(DemoContentSeeder::class);

        $partner = LphPartner::firstOrFail();
        $location = HalalLocation::where('lph_partner_id', $partner->id)->firstOrFail();

        $response = $this->getJson('/api/map?category='.urlencode($location->category).'&lph_partner_id='.$partner->id);

        $response->assertOk();
        $response->assertJsonPath('data.locations.0.category', $location->category);
        $response->assertJsonPath('data.locations.0.lph_partner.id', $partner->id);
    }
}
