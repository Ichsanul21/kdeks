<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeocodingService
{
    protected string $baseUrl = 'https://nominatim.openstreetmap.org';

    /**
     * Search coordinates for an address.
     */
    public function search(string $address): ?array
    {
        try {
            // Respect Nominatim Usage Policy (User-Agent is required)
            $response = Http::withHeaders([
                'User-Agent' => 'KDEKS-Halal-Center/1.0 (contact@kdeks-kaltim.id)'
            ])->get("{$this->baseUrl}/search", [
                'q' => $address,
                'format' => 'jsonv2',
                'limit' => 1,
            ]);

            if ($response->successful() && !empty($response->json())) {
                $result = $response->json()[0];
                return [
                    'lat' => (float) $result['lat'],
                    'lng' => (float) $result['lon'],
                    'display_name' => $result['display_name'],
                ];
            }
        } catch (\Exception $e) {
            Log::error('Geocoding error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Search coordinates specifically for Kaltim region if possible.
     */
    public function searchInKaltim(string $query): ?array
    {
        // Append Kalimantan Timur if not present
        if (!str_contains(strtolower($query), 'kalimantan timur')) {
            $query .= ', Kalimantan Timur, Indonesia';
        }

        return $this->search($query);
    }
}
