<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('category')) {
            $categorySlug = $request->query('category');
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        $products = $query->latest()->get();
        $articles = \App\Models\Article::latest()->take(3)->get();

        return view('welcome', compact('products', 'articles'));
    }

    public function catalog(Request $request)
    {
        $query = Product::with('category');

        if ($request->has('category')) {
            $categorySlug = $request->query('category');
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        $products = $query->latest()->get();
        $categories = \App\Models\Category::all();

        return view('front.catalog', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        return view('front.products.show', compact('product'));
    }
}
