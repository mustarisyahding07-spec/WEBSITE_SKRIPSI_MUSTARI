<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\KomerceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ShippingController extends Controller
{
    protected KomerceService $komerce;

    public function __construct(KomerceService $komerce)
    {
        $this->komerce = $komerce;
    }

    /**
     * Get all provinces
     */
    public function getProvinces()
    {
        if (!$this->komerce->isConfigured()) {
            return response()->json([
                'success' => false,
                'message' => 'Komerce API key belum dikonfigurasi'
            ], 500);
        }

        $provinces = $this->komerce->getProvinces();

        return response()->json([
            'success' => true,
            'data' => $provinces
        ]);
    }

    /**
     * Get cities, optionally filtered by province
     */
    public function getCities(Request $request)
    {
        if (!$this->komerce->isConfigured()) {
            return response()->json([
                'success' => false,
                'message' => 'Komerce API key belum dikonfigurasi'
            ], 500);
        }

        $provinceId = $request->query('province_id');
        $cities = $this->komerce->getCities($provinceId);

        return response()->json([
            'success' => true,
            'data' => $cities
        ]);
    }

    /**
     * Find city by postal code
     */
    public function findCityByPostalCode(Request $request)
    {
        $request->validate([
            'postal_code' => 'required|string|min:5|max:5'
        ]);

        if (!$this->komerce->isConfigured()) {
            return response()->json([
                'success' => false,
                'message' => 'Komerce API key belum dikonfigurasi'
            ], 500);
        }

        $city = $this->komerce->getCityByPostalCode($request->postal_code);

        if (!$city) {
            return response()->json([
                'success' => false,
                'message' => 'Kota tidak ditemukan untuk kode pos tersebut'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $city
        ]);
    }

    /**
     * Calculate shipping cost
     */
    public function calculateCost(Request $request)
    {
        // 1. Validation
        $request->validate([
            'destination_city_id' => 'required',
            'weight' => 'required|integer|min:1'
        ]);

        // 2. Configuration (Direct from Env to be safe)
        $apiKey = env('KOMERCE_API_KEY', 'k02CWHYob5329ccf5fbf403fKMubq2tw'); 
        $origin = env('KOMERCE_ORIGIN_CITY', 398); 
        $destination = $request->destination_city_id;
        $weight = $request->weight;

        // 3. Direct API Calls (Bypass Service & SSL Verification)
        $results = [];
        // Removed 'anteraja' to avoid 404s
        $couriers = ['jne', 'sicepat', 'jnt', 'idexpress']; 

        foreach ($couriers as $courier) {
            try {
                $response = Http::withoutVerifying() // Fix for local SSL issues
                    ->asForm()
                    ->withHeaders(['key' => $apiKey])
                    ->post("https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost", [
                        'origin' => $origin,
                        'destination' => $destination,
                        'weight' => $weight,
                        'courier' => $courier
                    ]);

                if ($response->successful()) {
                    $json = $response->json();
                    $data = $json['data'] ?? [];
                    
                    // Simplify mapping
                    foreach ($data as $item) {
                        $results[] = [
                            'courier' => strtoupper($item['code'] ?? $courier),
                            'service' => $item['service'] ?? '-',
                            'description' => $item['description'] ?? '-',
                            'cost' => $item['cost'] ?? 0,
                            'etd' => $item['etd'] ?? '-'
                        ];
                    }
                }
            } catch (\Exception $e) {
                // Silently ignore failures to allow other couriers to show
                \Illuminate\Support\Facades\Log::error("Direct Shipping Error ({$courier}): " . $e->getMessage());
            }
        }
        
        // 4. Sort by Price (Cheapest first)
        usort($results, fn($a, $b) => $a['cost'] <=> $b['cost']);

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }

    /**
     * Reverse geocode coordinates to get address info
     * Uses Nominatim (OpenStreetMap) - free, no API key needed
     */
    public function reverseGeocode(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric'
        ]);

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'IvoKaryaApp/1.0'
            ])->get('https://nominatim.openstreetmap.org/reverse', [
                'format' => 'json',
                'lat' => $request->lat,
                'lon' => $request->lng,
                'addressdetails' => 1,
                'accept-language' => 'id'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $address = $data['address'] ?? [];

                return response()->json([
                    'success' => true,
                    'data' => [
                        'display_name' => $data['display_name'] ?? '',
                        'postal_code' => $address['postcode'] ?? '',
                        'village' => $address['village'] ?? $address['suburb'] ?? '',
                        'district' => $address['county'] ?? $address['city_district'] ?? '',
                        'city' => $address['city'] ?? $address['town'] ?? $address['county'] ?? '',
                        'state' => $address['state'] ?? '',
                        'country' => $address['country'] ?? 'Indonesia',
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal mendapatkan alamat'
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
