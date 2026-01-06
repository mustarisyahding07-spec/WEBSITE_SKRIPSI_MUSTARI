<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Abon Ivo Karya - Kelezatan Tradisional</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Outfit:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .font-display { font-family: 'Outfit', sans-serif; }
        .font-sans { font-family: 'Inter', sans-serif; }
        .animate-slow-zoom { animation: slowZoom 20s infinite alternate; }
        @keyframes slowZoom { from { transform: scale(1); } to { transform: scale(1.1); } }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-white selection:bg-brand-500 selection:text-white">

    <!-- Navigation -->
    <!-- Navigation -->
    @include('components.public-navbar')

    <!-- Hero Section -->
    <section class="relative h-screen flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            <!-- Ensure this image is correct relative to public/img -->
            <img src="{{ asset('img/premium/product-group.jpg') }}" alt="Hero Background" class="w-full h-full object-cover opacity-60 scale-105 animate-slow-zoom">
            <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-transparent to-black"></div>
        </div>
        
        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto" 
             x-data="{ show: false }" 
             x-init="setTimeout(() => show = true, 500)">
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-display font-bold text-white mb-6 tracking-tight leading-tight"
                x-show="show" 
                x-transition:enter="transition ease-out duration-1000"
                x-transition:enter-start="opacity-0 translate-y-10"
                x-transition:enter-end="opacity-100 translate-y-0">
                Murni. Otentik. <span class="text-brand-500">Istimewa.</span>
            </h1>
            <p class="text-lg md:text-xl text-gray-200 font-light tracking-wide max-w-2xl mx-auto mb-10"
               x-show="show" 
               x-transition:enter="transition ease-out duration-1000 delay-300"
               x-transition:enter-start="opacity-0 translate-y-10"
               x-transition:enter-end="opacity-100 translate-y-0">
               Rasakan kenikmatan cita rasa tradisional Abon Sidrap. Dibuat dengan penuh hati untuk kualitas tak tertandingi.
            </p>
            
            <!-- Scroll Indicator -->
            <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce"
                 x-show="show"
                 x-transition:enter="transition ease-in duration-1000 delay-700"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100">
                <a href="#story" class="text-white/80 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Storytelling Section 1: Texture -->
    <section id="story" class="py-24 bg-black text-white overflow-hidden">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center gap-16">
                <div class="md:w-1/2" x-intersect="$el.classList.add('opacity-100', 'translate-x-0')" class="opacity-0 -translate-x-20 transition-all duration-1000 ease-out">
                    <span class="text-brand-500 font-bold tracking-widest text-sm uppercase mb-2 block">Kualitas Tanpa Kompromi</span>
                    <h2 class="text-4xl md:text-5xl font-display font-bold mb-6">Serart Demi Serat</h2>
                    <p class="text-gray-400 text-lg leading-relaxed mb-6">
                        Setiap helai abon kami adalah bukti dedikasi. Menggunakan bahan dasar 100% daging segar pilihan, diolah dengan teknik 'slow-cooking' untuk mempertahankan tekstur yang lembut namun renyah.
                    </p>
                    <p class="text-gray-400 text-lg leading-relaxed">
                        Kami tidak hanya membuat makanan, kami menciptakan pengalaman rasa yang akan membawa Anda kembali ke kehangatan masakan rumah.
                    </p>
                </div>
                <div class="md:w-1/2 relative group" x-intersect="$el.classList.add('opacity-100', 'translate-x-0')" class="opacity-0 translate-x-20 transition-all duration-1000 ease-out delay-200">
                    <div class="absolute inset-0 bg-brand-500/10 blur-3xl rounded-full opacity-0 group-hover:opacity-30 transition-opacity duration-700"></div>
                    <img src="{{ asset('img/premium/texture.jpg') }}" alt="Texture Detail" class="relative z-10 w-full rounded-2xl shadow-2xl skew-y-3 group-hover:skew-y-0 transition-transform duration-700 ease-in-out">
                </div>
            </div>
        </div>
    </section>

    <!-- Detail Feature Section -->
    <section class="py-24 bg-zinc-900 border-t border-white/10">
        <div class="container mx-auto px-6 text-center">
             <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                 <!-- Feature 1 -->
                 <div class="p-8 group hover:bg-white/5 rounded-2xl transition-colors duration-300" x-intersect="$el.classList.add('opacity-100', 'translate-y-0')" class="opacity-0 translate-y-10 transition-all duration-700 ease-out">
                     <div class="h-16 w-16 bg-brand-900/50 rounded-full flex items-center justify-center mx-auto mb-6 text-brand-400 group-hover:scale-110 transition-transform duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                     </div>
                     <h3 class="text-xl font-bold text-white mb-3">Warisan Sejak 2016</h3>
                     <p class="text-gray-400">Resep turun-temurun yang telah disempurnakan selama bertahun-tahun.</p>
                 </div>
                 
                 <!-- Feature 2 -->
                 <div class="p-8 group hover:bg-white/5 rounded-2xl transition-colors duration-300" x-intersect="$el.classList.add('opacity-100', 'translate-y-0')" class="opacity-0 translate-y-10 transition-all duration-700 ease-out delay-150">
                    <div class="h-16 w-16 bg-brand-900/50 rounded-full flex items-center justify-center mx-auto mb-6 text-brand-400 group-hover:scale-110 transition-transform duration-300">
                       <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                       </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">100% Alami</h3>
                    <p class="text-gray-400">Tanpa bahan pengawet buatan. Murni kebaikan alam.</p>
                </div>

                <!-- Feature 3 -->
                <div class="p-8 group hover:bg-white/5 rounded-2xl transition-colors duration-300" x-intersect="$el.classList.add('opacity-100', 'translate-y-0')" class="opacity-0 translate-y-10 transition-all duration-700 ease-out delay-300">
                    <div class="h-16 w-16 bg-brand-900/50 rounded-full flex items-center justify-center mx-auto mb-6 text-brand-400 group-hover:scale-110 transition-transform duration-300">
                       <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                       </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Tinggi Protein</h3>
                    <p class="text-gray-400">Pilihan cerdas dan sehat untuk asupan nutrisi keluarga Anda.</p>
                </div>
             </div>
        </div>
    </section>

    <!-- Shop CTA Transition -->
    <section class="relative py-32 flex items-center justify-center bg-fixed bg-center bg-cover" style="background-image: url('{{ asset('img/premium/process.jpg') }}');">
        <div class="absolute inset-0 bg-black/70"></div>
        <div class="relative z-10 text-center px-6">
            <h2 class="text-4xl md:text-6xl font-display font-bold text-white mb-8">Siap Merasakan Kelezatan Luar Biasa?</h2>
            <a href="#shop-section" class="inline-block bg-white text-black px-10 py-4 rounded-full font-bold text-lg hover:bg-brand-500 hover:text-white transition-all duration-300 transform hover:scale-105 shadow-xl">
                Belanja Sekarang
            </a>
        </div>
    </section>

    <!-- Product Showcase -->
    <section id="shop-section" class="py-24 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-brand-600 font-bold tracking-widest text-sm uppercase mb-2 block">Koleksi Kami</span>
                <h2 class="text-4xl md:text-5xl font-display font-bold text-gray-900">Produk Pilihan</h2>
            </div>
            
            <div class="flex justify-center">
                <a href="{{ route('products.index') }}" class="inline-block bg-gray-900 text-white px-12 py-5 rounded-full font-bold text-lg hover:bg-brand-600 transition-all duration-300 transform hover:scale-105 shadow-xl">
                    Lihat Produk Kami
                </a>
            </div>
            

        </div>
    </section>

    <!-- Articles Section -->
    <section id="articles-section" class="py-24 bg-white border-t border-gray-100">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-brand-600 font-bold tracking-widest text-sm uppercase mb-2 block">Wawasan & Info</span>
                <h2 class="text-4xl md:text-5xl font-display font-bold text-gray-900">Edukasi & Tips</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                @forelse($articles as $article)
                <div class="group cursor-pointer">
                    <div class="overflow-hidden rounded-2xl mb-6 relative h-64">
                         @if($article->thumbnail)
                            <img src="{{ Str::startsWith($article->thumbnail, 'http') ? $article->thumbnail : Storage::url($article->thumbnail) }}" 
                                 alt="{{ $article->title }}" 
                                 class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                         @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400">No Image</span>
                            </div>
                         @endif
                         <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors duration-300"></div>
                    </div>
                    <div class="text-sm text-brand-600 font-bold mb-2 uppercase">{{ $article->category->name ?? 'Info' }}</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-brand-600 transition-colors leading-tight">
                        <a href="{{ route('articles.show', $article->slug) }}">
                            {{ $article->title }}
                        </a>
                    </h3>
                    <p class="text-gray-500 line-clamp-3 mb-4">{{ Str::limit(strip_tags($article->content), 120) }}</p>
                    <a href="{{ route('articles.show', $article->slug) }}" class="text-gray-900 font-bold hover:text-brand-600 transition-colors inline-flex items-center">
                        Baca Selengkapnya <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
                @empty
                <div class="col-span-full text-center">
                    <p class="text-gray-400">Belum ada artikel terbaru.</p>
                </div>
                @endforelse
            </div>
            
             <div class="text-center mt-12">
                <a href="{{ route('articles.index') }}" class="inline-block border-2 border-gray-900 text-gray-900 px-8 py-3 rounded-full font-bold hover:bg-gray-900 hover:text-white transition-all duration-300">
                    Lihat Semua Artikel
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black text-white py-12 border-t border-gray-900">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <h3 class="text-2xl font-display font-bold mb-4">IVO KARYA</h3>
                    <p class="text-gray-400 max-w-sm">Kualitas terbaik dari hati kami untuk keluarga Anda. Rasakan bedanya abon premium yang sesungguhnya.</p>
                </div>
                <div>
                     <h4 class="font-bold mb-4 text-brand-500">Tautan</h4>
                     <ul class="space-y-2 text-gray-400">
                         <li><a href="#" class="hover:text-white transition-colors">Beranda</a></li>
                         <li><a href="#story" class="hover:text-white transition-colors">Cerita Kami</a></li>
                         <li><a href="#shop-section" class="hover:text-white transition-colors">Produk</a></li>
                         <li><a href="#" class="hover:text-white transition-colors">Kontak</a></li>
                         <li><a href="{{ url('/admin') }}" class="hover:text-gray-200 text-gray-600 text-xs transition-colors">Admin</a></li>
                     </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4 text-brand-500">Hubungi Kami</h4>
                    <p class="text-gray-400 mb-2">Sidrap, Sulawesi Selatan</p>
                    <p class="text-gray-400 mb-2">+62 812 3456 7890</p>
                    <p class="text-gray-400">info@ivo-karya.com</p>
                </div>
            </div>
            <div class="border-t border-white/10 mt-12 pt-8 text-center text-gray-500 text-sm">
                &copy; {{ date('Y') }} UMKM Ivo Karya. All rights reserved.
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
