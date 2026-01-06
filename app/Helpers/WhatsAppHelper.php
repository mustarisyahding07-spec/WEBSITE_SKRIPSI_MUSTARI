<?php

namespace App\Helpers;

use App\Models\Setting;

class WhatsAppHelper
{
    public static function getAdminNumber()
    {
        // Default number if setting not found
        $default = '628212345678'; 
        
        $setting = Setting::where('key', 'admin_whatsapp_number')->first();
        
        if ($setting && !empty($setting->value)) {
            // Clean number: remove non-numeric, ensure 62 prefix
            $number = preg_replace('/[^0-9]/', '', $setting->value);
            if (str_starts_with($number, '0')) {
                $number = '62' . substr($number, 1);
            }
            return $number;
        }

        return $default;
    }

    public static function getLink($message, $number = null)
    {
        $targetNumber = $number ?? self::getAdminNumber();
        return "https://wa.me/{$targetNumber}?text=" . urlencode($message);
    }
}
