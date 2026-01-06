<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;

class FonnteService
{
    /**
     * Send a message via Fonnte API.
     *
     * @param string $target The phone number (e.g., '08123456789' or '628123456789')
     * @param string $message The message content
     * @return array|null Response body or null on failure
     */
    public static function send($target, $message)
    {
        $apiKey = Setting::where('key', 'fonnte_api_key')->value('value');

        if (empty($apiKey)) {
            Log::warning('Fonnte API Key is missing in Settings.');
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $apiKey,
            ])->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
                'countryCode' => '62', // Default country code
            ]);

            if ($response->failed()) {
                Log::error('Fonnte API Error: ' . $response->body());
                return null;
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Fonnte Connection Error: ' . $e->getMessage());
            return null;
        }
    }
}
