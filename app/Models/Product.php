<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'weight',
        'image',
        'category_id', // Changed from category
        'discount_price',
        'discount_percentage',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'stock' => 'integer',
        'weight' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->discount_price && $this->discount_price < $this->price) {
            return round((($this->price - $this->discount_price) / $this->price) * 100);
        }
        return 0;
    }
}
