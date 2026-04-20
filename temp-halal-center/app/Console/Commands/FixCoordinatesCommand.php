<?php

namespace App\Console\Commands;

use App\Models\HalalLocation;
use App\Models\Umkm;
use App\Services\GeocodingService;
use Illuminate\Console\Command;

class FixCoordinatesCommand extends Command
{
    protected $signature = 'umkm:fix-coordinates {--all : Process all records, even if they have coordinates} {--force : Update records that are outside Kaltim}';
    protected $description = 'Fix missing or suspicious coordinates for UMKMs and Halal Locations';

    protected GeocodingService $geocoder;

    public function __construct(GeocodingService $geocoder)
    {
        parent::__construct();
        $this->geocoder = $geocoder;
    }

    public function handle()
    {
        $this->info('Starting coordinate fix process...');

        $this->processUmkms();
        $this->processHalalLocations();

        $this->info('Process completed.');
    }

    protected function isSuspicious($lat, $lng)
    {
        if (empty($lat) || empty($lng)) return true;
        // East Kalimantan roughly Lat -3 to 5, Lng 113 to 120
        return ($lat < -4.5 || $lat > 5.5 || $lng < 113 || $lng > 120);
    }

    protected function processUmkms()
    {
        $query = Umkm::query();

        if (!$this->option('all')) {
            $query->where(function ($q) {
                $q->whereNull('latitude')
                  ->orWhereNull('longitude')
                  ->orWhere('latitude', 0)
                  ->orWhere('longitude', 0);
                
                if ($this->option('force')) {
                    // Inclusion of suspicious ones will be handled in loop if not 'all'
                }
            });
        }

        $items = $query->get();
        $this->info("Processing " . $items->count() . " UMKMs...");

        foreach ($items as $item) {
            if (!$this->option('all') && !$this->isSuspicious($item->latitude, $item->longitude) && !$this->option('force')) {
                continue;
            }

            if (!$this->option('all') && !$this->isSuspicious($item->latitude, $item->longitude) && $this->option('force') && $item->latitude != 0) {
                // If not suspicious and not 0, skip unless --all
                continue;
            }

            $address = $item->alamat;
            if (empty($address)) {
                $address = ($item->kab_kota ?? '') . ' ' . ($item->provinsi ?? 'Kalimantan Timur');
            }

            if (empty(trim($address))) continue;

            $this->comment("Geocoding UMKM [{$item->id}]: {$item->nama_umkm} -> {$address}");
            
            $coords = $this->geocoder->searchInKaltim($address);
            
            if (!$coords) {
                // Fallback 1: Name + City
                $simpleAddress = $item->nama_umkm . ', ' . ($item->kab_kota ?? 'Kalimantan Timur');
                $coords = $this->geocoder->searchInKaltim($simpleAddress);
            }

            if (!$coords && $item->kab_kota) {
                // Fallback 2: Just City
                $coords = $this->geocoder->searchInKaltim($item->kab_kota);
            }
            
            if ($coords) {
                $item->update([
                    'latitude' => $coords['lat'],
                    'longitude' => $coords['lng'],
                ]);
                $this->info("  Success: {$coords['lat']}, {$coords['lng']}");
            } else {
                $this->error("  Failed to geocode.");
            }
            
            // Wait 1 second to respect Nominatim usage policy
            sleep(1);
        }
    }

    protected function processHalalLocations()
    {
        $query = HalalLocation::query();
        $items = $query->get();

        $this->info("Processing " . $items->count() . " Halal Locations...");

        foreach ($items as $item) {
            if (!$this->isSuspicious($item->latitude, $item->longitude) && !$this->option('all')) {
                continue;
            }

            $address = $item->address;
            if (empty($address)) {
                $address = ($item->city_name ?? '') . ' Kalimantan Timur';
            }

            $this->comment("Geocoding Location [{$item->id}]: {$item->name} -> {$address}");
            $coords = $this->geocoder->searchInKaltim($address);

            if ($coords) {
                $item->update([
                    'latitude' => $coords['lat'],
                    'longitude' => $coords['lng'],
                ]);
                $this->info("  Success: {$coords['lat']}, {$coords['lng']}");
            } else {
                $this->error("  Failed.");
            }

            sleep(1);
        }
    }
}
