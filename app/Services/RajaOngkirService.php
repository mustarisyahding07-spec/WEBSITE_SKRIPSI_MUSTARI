<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class RajaOngkirService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://api.rajaongkir.com/starter';
    protected string $originCityId;

    public function __construct()
    {
        $this->apiKey = config('services.rajaongkir.key', '');
        $this->originCityId = config('services.rajaongkir.origin_city', '398'); // Sidenreng Rappang
    }

    /**
     * Get all provinces from RajaOngkir
     */
    public function getProvinces(): ?array
    {
        return Cache::remember('rajaongkir_provinces', 86400, function () {
            try {
                $response = Http::withHeaders([
                    'key' => $this->apiKey,
                ])->get("{$this->baseUrl}/province");

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['rajaongkir']['results'] ?? [];
                }

                Log::error('RajaOngkir Province Error: ' . $response->body());
                return null;
            } catch (\Exception $e) {
                Log::error('RajaOngkir Province Exception: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Get all cities, optionally filtered by province
     */
    public function getCities(?string $provinceId = null): ?array
    {
        $cacheKey = 'rajaongkir_cities_' . ($provinceId ?? 'all');

        return Cache::remember($cacheKey, 86400, function () use ($provinceId) {
            try {
                $params = [];
                if ($provinceId) {
                    $params['province'] = $provinceId;
                }

                $response = Http::withHeaders([
                    'key' => $this->apiKey,
                ])->get("{$this->baseUrl}/city", $params);

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['rajaongkir']['results'] ?? [];
                }

                Log::error('RajaOngkir Cities Error: ' . $response->body());
                return null;
            } catch (\Exception $e) {
                Log::error('RajaOngkir Cities Exception: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Find city by postal code
     */
    public function getCityByPostalCode(string $postalCode): ?array
    {
        $cities = $this->getCities();
        
        if (!$cities) {
            return null;
        }

        foreach ($cities as $city) {
            if ($city['postal_code'] == $postalCode) {
                return $city;
            }
        }

        // If exact match not found, try partial match (first 3 digits)
        $prefix = substr($postalCode, 0, 3);
        foreach ($cities as $city) {
            if (str_starts_with($city['postal_code'], $prefix)) {
                return $city;
            }
        }

        return null;
    }

    /**
     * Calculate shipping cost
     */
    public function getCost(string $destinationCityId, int $weight, string $courier = 'jne'): ?array
    {
        try {
            $response = Http::withHeaders([
                'key' => $this->apiKey,
            ])->post("{$this->baseUrl}/cost", [
                'origin' => $this->originCityId,
                'destination' => $destinationCityId,
                'weight' => $weight,
                'courier' => $courier,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['rajaongkir']['results'] ?? [];
            }

            Log::error('RajaOngkir Cost Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('RajaOngkir Cost Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get shipping costs for all available couriers
     */
    public function getAllCourierCosts(string $destinationCityId, int $weight): array
    {
        $couriers = ['jne', 'tiki', 'pos'];
        $results = [];

        foreach ($couriers as $courier) {
            $costs = $this->getCost($destinationCityId, $weight, $courier);
            if ($costs) {
                foreach ($costs as $result) {
                    $courierName = strtoupper($result['code']);
                    foreach ($result['costs'] as $service) {
                        $results[] = [
                            'courier' => $courierName,
                            'service' => $service['service'],
                            'description' => $service['description'],
                            'cost' => $service['cost'][0]['value'] ?? 0,
                            'etd' => $service['cost'][0]['etd'] ?? '-',
                        ];
                    }
                }
            }
        }

        // Sort by cost ascending
        usort($results, fn($a, $b) => $a['cost'] <=> $b['cost']);

        return $results;
    }

    /**
     * Check if API key is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Get origin city ID
     */
    public function getOriginCityId(): string
    {
        return $this->originCityId;
    }
}
