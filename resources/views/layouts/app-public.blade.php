<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'UMKM Ivo Karya - Abon Ikan & Sapi Khas Sidrap')</title>
        @stack('seo')
        <!-- Dynamic SEO -->
        @if(isset($product))
            <meta name="description" content="{{ $product->meta_description ?? Str::limit(strip_tags($product->description), 160) }}">
            <meta property="og:title" content="{{ $product->meta_title ?? $product->name }}">
            <meta property="og:description" content="{{ $product->meta_description ?? Str::limit(strip_tags($product->description), 160) }}">
            <meta property="og:image" content="{{ $product->image ? Storage::url($product->image) : asset('img/logo.png') }}">
        @elseif(isset($article))
            <meta name="description" content="{{ Str::limit(strip_tags($article->content), 160) }}">
             <meta property="og:title" content="{{ $article->title }}">
        @else
            <meta name="description" content="Abon Ikan dan Sapi terbaik dari Sidenreng Rappang. Halal, Enak, dan Tanpa Pengawet.">
        @endif
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;900&family=Outfit:wght@300;400;700&display=swap" rel="stylesheet">

        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                            display: ['Outfit', 'sans-serif'],
                        },
                        colors: {
                            brand: {
                                500: '#8B4513', // Saddle Brown
                                600: '#723A0F',
                            },
                            black: '#000000',
                            white: '#FFFFFF',
                        }
                    }
                }
            }
        </script>
        <style>
            .hero-pattern {
                background-color: #F5F5DC;
                background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%238B4513' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            }
        </style>
        @livewireStyles
    </head>
    <body class="antialiased bg-gray-50 text-gray-800 flex flex-col min-h-screen">
        
        <!-- Navigation -->
        @include('components.public-navbar')
        
        <!-- Cart Notification Modal -->
        @include('components.cart-notification-modal')

        <div class="flex-grow">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-12 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-6 md:mb-0 text-center md:text-left">
                        <span class="font-bold text-2xl text-white">Ivo Karya</span>
                        <p class="text-gray-400 mt-2 text-sm">Abon Ikan & Sapi Lezat Khas Sidenreng Rappang.</p>
                    </div>
                    <div class="flex space-x-6">
                        <a href="#" class="text-gray-400 hover:text-white transition">Instagram</a>
                        <a href="#" class="text-gray-400 hover:text-white transition">Facebook</a>
                        <a href="#" class="text-gray-400 hover:text-white transition">WhatsApp</a>
                        <a href="{{ url('/admin/login') }}" class="text-gray-600 hover:text-gray-400 transition text-xs">Admin</a>
                    </div>
                </div>
                <div class="mt-8 border-t border-gray-800 pt-8 text-center text-gray-500 text-sm">
                    &copy; 2026 UMKM Ivo Karya. Hak Cipta Dilindungi.
                </div>
            </div>
        </footer>

        @livewireScripts
    </body>
</html>
