<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class KomerceService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://rajaongkir.komerce.id/api/v1';
    protected string $originCityId;

    public function __construct()
    {
        $this->apiKey = config('services.komerce.key', '');
        $this->originCityId = config('services.komerce.origin_city', '398'); // Sidenreng Rappang
    }

    /**
     * Get all provinces
     * Uses generic search to mimic province list or returns empty if not supported
     */
    public function getProvinces(): ?array
    {
        // Komerce does not have a comprehensive province list endpoint compatible with RajaOngkir's simple list.
        // We return empty array as the frontend now relies on postal code / location search.
        return [];
    }

    /**
     * Get all cities
     */
    public function getCities(?string $provinceId = null): ?array
    {
        return [];
    }

    /**
     * Find city by postal code
     * Uses Komerce API: /destination/domestic-destination?search={postalCode}
     */
    public function getCityByPostalCode(string $postalCode): ?array
    {
        return Cache::remember('komerce_city_' . $postalCode, 86400, function () use ($postalCode) {
            try {
                $response = Http::withHeaders([
                    'key' => $this->apiKey,
                ])->get("{$this->baseUrl}/destination/domestic-destination", [
                    'search' => $postalCode
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $results = $data['data'] ?? [];
                    
                    if (empty($results)) {
                        return null;
                    }

                    // Return the first match
                    $match = $results[0];
                    
                    // Map Komerce response to expected format
                    return [
                        'city_id' => $match['id'], // Use the specific destination ID
                        'province_id' => 0, // Not available/needed
                        'province' => $match['province_name'],
                        'type' => 'Kota/Kab', // Generic type
                        'city_name' => $match['city_name'],
                        'postal_code' => $match['zip_code'],
                        'district' => $match['district_name'] ?? '',
                        'subdistrict' => $match['subdistrict_name'] ?? ''
                    ];
                }

                Log::error('Komerce City Search Error: ' . $response->body());
                return null;
            } catch (\Exception $e) {
                Log::error('Komerce City Search Exception: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Calculate shipping cost
     * Komerce endpoint: /calculate/domestic-cost
     */
    public function getCost(string $destinationId, int $weight, string $courier = 'jne'): ?array
    {
        try {
            Log::info("Komerce Cost Request: Origin={$this->originCityId}, Dest={$destinationId}, Weight={$weight}, Courier={$courier}");

            // Updated: Use asForm() for x-www-form-urlencoded
            $response = Http::asForm()->withHeaders([
                'key' => $this->apiKey,
            ])->post("{$this->baseUrl}/calculate/domestic-cost", [
                'origin' => $this->originCityId,
                'destination' => $destinationId,
                'weight' => $weight,
                'courier' => $courier,
            ]);

            Log::info("Komerce Cost Response ({$courier}): " . $response->body());

            if ($response->successful()) {
                $data = $response->json();
                return $data['data'] ?? [];
            }

            Log::error("Komerce Cost Error (Org:{$this->originCityId}, Dst:{$destinationId}, Wgt:{$weight}, Cour:{$courier}): " . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Komerce Cost Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get shipping costs for all available couriers
     */
    public function getAllCourierCosts(string $destinationId, int $weight): array
    {
        Log::info("KOMERCE SERVICE V-FINAL: Checking costs for Dest:{$destinationId}");
        // Anteraja removed due to API errors
        $couriers = ['jne', 'sicepat', 'jnt', 'idexpress']; 
        $results = [];

        foreach ($couriers as $courier) {
            $costs = $this->getCost($destinationId, $weight, $courier);
            
            if ($costs) {
                Log::info("Courier {$courier} returned " . count($costs) . " rates");
                // Komerce returns a flat array of services
                foreach ($costs as $result) {
                    $results[] = [
                        'courier' => strtoupper($result['code'] ?? $courier),
                        'service' => $result['service'] ?? '-',
                        'description' => $result['description'] ?? ($result['service'] ?? '-'),
                        'cost' => $result['cost'] ?? 0,
                        'etd' => $result['etd'] ?? '-',
                    ];
                }
            }
        }

        Log::info("Total Shipping Options Found: " . count($results));
        usort($results, fn($a, $b) => $a['cost'] <=> $b['cost']);

        return $results;
    }

    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }
}
