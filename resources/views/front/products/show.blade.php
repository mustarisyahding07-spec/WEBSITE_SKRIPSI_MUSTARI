@extends('layouts.app-public')

@section('title', $product->meta_title ?? $product->name . ' - UMKM Ivo Karya')

@push('seo')
    <meta name="description" content="{{ $product->meta_description ?? Str::limit(strip_tags($product->description), 160) }}">
    <meta property="og:title" content="{{ $product->meta_title ?? $product->name }}">
    <meta property="og:description" content="{{ $product->meta_description ?? Str::limit(strip_tags($product->description), 160) }}">
    <meta property="og:image" content="{{ Storage::url($product->image) }}">
@endpush

@section('content')
<div class="relative bg-black pt-32 pb-20 overflow-hidden">
    <div class="absolute inset-0 z-0">
        @if($product->image)
             <img src="{{ Storage::url($product->image) }}" alt="Background" class="w-full h-full object-cover opacity-20 blur-sm">
        @else
             <img src="{{ asset('img/premium/texture.jpg') }}" alt="Background" class="w-full h-full object-cover opacity-30">
        @endif
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-white"></div>
    </div>
    <div class="container mx-auto px-6 relative z-10 text-center">
        <h1 class="text-4xl md:text-5xl font-display font-bold text-white mb-2">{{ $product->name }}</h1>
        <p class="text-gray-300 hover:text-white transition-colors"><a href="{{ route('home') }}">Beranda</a> / Produk / {{ $product->name }}</p>
    </div>
</div>

<div class="py-12 bg-white -mt-10 relative z-10 rounded-t-3xl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <!-- Product Image -->
            <div class="bg-gray-100 rounded-xl overflow-hidden shadow-sm">
                @if($product->image)
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                    <div class="flex items-center justify-center h-96 text-gray-400">
                        <svg class="h-24 w-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                @endif
            </div>

            <!-- Product Details -->
            <div>
                 <div class="flex justify-between items-start">
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-2">{{ $product->name }}</h2>
                    @if($product->weight)
                        <span class="bg-gray-200 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $product->weight }} gr</span>
                    @endif
                 </div>
                 
                 <div class="mb-4">
                     <span class="text-sm text-brand-500 font-semibold uppercase tracking-wider">
                         {{ $product->category ? $product->category->name : 'Uncategorized' }}
                     </span>
                 </div>

                 <!-- Short Description (below product name) -->
                 @if($product->description)
                 <div class="prose prose-sm max-w-none text-gray-600 mb-6">
                     {!! $product->description !!}
                 </div>
                 @endif

                 <!-- Pricing -->
                 <div class="mb-6 flex items-center space-x-3">
                     @if($product->discount_price && $product->discount_price < $product->price)
                         <span class="text-3xl font-bold text-red-600">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</span>
                         <span class="text-lg text-gray-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                         <span class="bg-red-100 text-red-800 text-sm font-bold px-2.5 py-0.5 rounded">-{{ $product->discount_percentage ?? round((($product->price - $product->discount_price)/$product->price)*100) }}%</span>
                     @else
                         <span class="text-3xl font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                     @endif
                 </div>

                 <!-- Add to Cart -->
                 <div>
                     <a href="{{ route('cart.add', $product->id) }}" class="w-full md:w-auto bg-brand-500 hover:bg-brand-600 text-white font-bold py-3 px-8 rounded-full shadow-lg transition duration-300 transform hover:-translate-y-1 flex items-center justify-center gap-2">
                         <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                         Tambah ke Keranjang
                     </a>
                 </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="mt-16 border-t border-gray-200 pt-10">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Ulasan Pelanggan</h2>
            @livewire('product-reviews', ['product' => $product])
        </div>

    </div>
</div>
@endsection
