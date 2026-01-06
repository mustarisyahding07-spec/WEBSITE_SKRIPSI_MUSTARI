@extends('layouts.app-public')

@section('title', 'Katalog Produk - Ivo Karya')

@section('content')
<div class="relative bg-black pt-32 pb-20 overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('img/premium/product-group.jpg') }}" alt="Background" class="w-full h-full object-cover opacity-30">
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-gray-50"></div>
    </div>
    
    <div class="container mx-auto px-6 relative z-10 text-center">
         <span class="text-brand-500 font-bold tracking-widest text-sm uppercase mb-2 block">Koleksi Lengkap</span>
         <h1 class="text-5xl md:text-6xl font-display font-bold text-white mb-6">Semua Produk</h1>
         <p class="text-gray-300 text-lg max-w-2xl mx-auto">Temukan berbagai varian abon ikan dan sapi terbaik khas Sidenreng Rappang.</p>
    </div>
</div>

<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-6">
        
        <!-- Category Filter -->
        <div class="flex justify-center flex-wrap gap-4 mb-12 -mt-8 relative z-20">
            <a href="{{ route('products.index') }}" class="px-6 py-2 rounded-full font-bold transition-all {{ !request('category') ? 'bg-brand-600 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-100' }}">
                Semua
            </a>
            @foreach($categories as $category)
            <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="px-6 py-2 rounded-full font-bold transition-all {{ request('category') == $category->slug ? 'bg-brand-600 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-100' }}">
                {{ $category->name }}
            </a>
            @endforeach
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @forelse($products as $product)
            <div class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 group">
                
                <div class="relative h-80 overflow-hidden bg-gray-100">
                     <a href="{{ route('product.show', $product->slug) }}" class="block w-full h-full">
                        <!-- Product Image -->
                        @if($product->image)
                        <img src="{{ Str::startsWith($product->image, 'http') ? $product->image : Storage::url($product->image) }}" 
                                alt="{{ $product->name }}" 
                                class="w-full h-full object-cover object-center group-hover:scale-110 transition-transform duration-700">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        @endif
                     </a>
                    
                    <!-- Discount Badge -->
                    @if($product->discount_percentage > 0)
                        <div class="absolute top-4 left-4 bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide pointer-events-none">
                            Hemat {{ $product->discount_percentage }}%
                        </div>
                    @endif
                </div>
                
                <div class="p-8">
                    <div class="text-sm text-gray-500 mb-2">{{ $product->category->name ?? 'Premium' }}</div>
                    <a href="{{ route('product.show', $product->slug) }}">
                         <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-brand-600 transition-colors">{{ $product->name }}</h3>
                    </a>
                    
                    <div class="flex items-baseline gap-3 mb-6">
                        @if($product->discount_price)
                            <span class="text-2xl font-bold text-gray-900">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</span>
                            <span class="text-base text-gray-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        @else
                            <span class="text-2xl font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        @endif
                    </div>
                    
                    <div x-data="{ quantity: 1 }">
                        <!-- Quantity & Buttons -->
                        <div class="flex items-center gap-3 mb-4">
                            <!-- Quantity Selector -->
                            <div class="flex items-center border border-gray-300 rounded-lg">
                                <button @click="quantity > 1 ? quantity-- : quantity" class="px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-l-lg">-</button>
                                <input type="number" x-model="quantity" class="w-12 text-center border-none focus:ring-0 p-0 text-sm" min="1" readonly>
                                <button @click="quantity++" class="px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-r-lg">+</button>
                            </div>

                            <!-- Add to Cart (Icon) -->
                            <button 
                               @click="
                                   fetch('{{ route('cart.add', $product->id) }}?quantity=' + quantity, {
                                       headers: { 'X-Requested-With': 'XMLHttpRequest' }
                                   })
                                   .then(response => response.json())
                                   .then(data => {
                                       $dispatch('cart-updated', { message: data.message, cart_count: data.cart_count });
                                   })
                               "
                               class="bg-gray-100 text-gray-800 p-3 rounded-lg hover:bg-gray-200 transition-colors cursor-pointer" title="Tambah ke Keranjang">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Buy Now (Full Width) -->
                        <a href="#" 
                           @click.prevent="window.location.href = '{{ route('cart.add', $product->id) }}?quantity=' + quantity + '&action=buy_now'"
                           class="block w-full text-center bg-gray-900 text-white py-3 rounded-xl font-bold hover:bg-brand-600 transition-colors duration-300">
                            Beli Sekarang
                        </a>
                    </div>
                </div>
            </div>
            @empty
                <div class="col-span-full text-center py-20">
                    <div class="mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-gray-300 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Produk Tidak Ditemukan</h3>
                    <p class="text-gray-500">Maaf, kami tidak menemukan produk untuk kategori ini.</p>
                    <a href="{{ route('products.index') }}" class="inline-block mt-6 text-brand-600 font-bold hover:underline">Lihat Semua Produk</a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
