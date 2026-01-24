<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_address',
        'customer_phone',
        'items_json',
        'total_amount',
        'total_weight',
        'status',
        'whatsapp_ref',
        'tracking_number',
        'tracking_token',
        'payment_proof',
        // Shipping fields
        'destination_city_id',
        'destination_city_name',
        'postal_code',
        'courier',
        'courier_service',
        'shipping_cost',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'items_json' => 'array',
        'total_amount' => 'decimal:2',
        'total_weight' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->tracking_token)) {
                $order->tracking_token = bin2hex(random_bytes(16));
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
