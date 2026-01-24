<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RajaOngkirService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ShippingController extends Controller
{
    protected RajaOngkirService $rajaOngkir;

    public function __construct(RajaOngkirService $rajaOngkir)
    {
        $this->rajaOngkir = $rajaOngkir;
    }

    /**
     * Get all provinces
     */
    public function getProvinces()
    {
        if (!$this->rajaOngkir->isConfigured()) {
            return response()->json([
                'success' => false,
                'message' => 'RajaOngkir API key belum dikonfigurasi'
            ], 500);
        }

        $provinces = $this->rajaOngkir->getProvinces();

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
        if (!$this->rajaOngkir->isConfigured()) {
            return response()->json([
                'success' => false,
                'message' => 'RajaOngkir API key belum dikonfigurasi'
            ], 500);
        }

        $provinceId = $request->query('province_id');
        $cities = $this->rajaOngkir->getCities($provinceId);

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

        if (!$this->rajaOngkir->isConfigured()) {
            return response()->json([
                'success' => false,
                'message' => 'RajaOngkir API key belum dikonfigurasi'
            ], 500);
        }

        $city = $this->rajaOngkir->getCityByPostalCode($request->postal_code);

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
        $request->validate([
            'destination_city_id' => 'required|string',
            'weight' => 'required|integer|min:1'
        ]);

        if (!$this->rajaOngkir->isConfigured()) {
            return response()->json([
                'success' => false,
                'message' => 'RajaOngkir API key belum dikonfigurasi'
            ], 500);
        }

        $costs = $this->rajaOngkir->getAllCourierCosts(
            $request->destination_city_id,
            $request->weight
        );

        return response()->json([
            'success' => true,
            'data' => $costs
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
