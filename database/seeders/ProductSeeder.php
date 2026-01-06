<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Category
        $catIkan = Category::create([
            'name' => 'Abon Ikan',
            'slug' => 'abon-ikan',
            'image' => 'premium/product-ikan.jpg' // Placeholder
        ]);

        $catSapi = Category::create([
            'name' => 'Abon Sapi',
            'slug' => 'abon-sapi',
            'image' => 'premium/product-sapi.jpg' // Placeholder
        ]);

        // Products
        Product::create([
            'name' => 'Abon Ikan Original (100g)',
            'slug' => 'abon-ikan-original-100g',
            'description' => 'Abon ikan tuna segar dengan bumbu rempah tradisional khas Sidrap. Tekstur halus, gurih, dan kaya protein. Cocok untuk lauk praktis keluarga.',
            'price' => 35000,
            'stock' => 50,
            'weight' => 100,
            'image' => 'premium/product-ikan.jpg', // We moved this to public/img/premium/product-ikan.jpg, but logic assumes storage. 
            // We need to fix the path or copy to storage/app/public/premium. 
            // For now let's assume I fix the storage link or path.
            'category_id' => $catIkan->id,
            'discount_price' => 29000,
            'discount_percentage' => 17,
            'meta_title' => 'Abon Ikan Tuna Asli Sidrap - Premium Quality',
            'meta_description' => 'Beli Abon Ikan Tuna asli khas Sidrap. Halal, tanpa pengawet, dan tinggi protein.'
        ]);

        Product::create([
            'name' => 'Abon Ikan Pedas (100g)',
            'slug' => 'abon-ikan-pedas-100g',
            'description' => 'Varian pedas dari abon ikan tuna kami. Perpaduan cabai segar dan rempah pilihan memberikan sensasi pedas yang nendang namun tetap nikmat.',
            'price' => 37000,
            'stock' => 45,
            'weight' => 100,
            'image' => 'premium/product-ikan.jpg',
            'category_id' => $catIkan->id,
            'discount_price' => 32000,
            'discount_percentage' => 13,
        ]);

        Product::create([
            'name' => 'Abon Sapi Premium (100g)',
            'slug' => 'abon-sapi-premium-100g',
            'description' => 'Abon sapi murni dari daging sapi pilihan bagian khas dalam. Serat daging terasa nyata, gurih manis, dan lumer di mulut.',
            'price' => 55000,
            'stock' => 30,
            'weight' => 100,
            'image' => 'premium/product-sapi.jpg',
            'category_id' => $catSapi->id,
            'meta_title' => 'Abon Sapi Murni Premium - Tanpa Campuran',
            'meta_description' => 'Abon sapi murni kualitas premium. Dibuat dari daging sapi segar tanpa campuran.'
        ]);
        
        Product::create([
            'name' => 'Abon Sapi Pedas (100g)',
            'slug' => 'abon-sapi-pedas-100g',
            'description' => 'Sensasi pedas manis abon sapi yang menggugah selera. Teman makan nasi hangat yang sempurna.',
            'price' => 58000,
            'discount_price' => 50000,
            'stock' => 25,
            'weight' => 100,
            'image' => 'premium/product-sapi.jpg', // Reuse for now
            'category_id' => $catSapi->id,
        ]);
    }
}
