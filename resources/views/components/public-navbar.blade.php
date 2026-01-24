<nav class="fixed w-full z-50 transition-all duration-300" 
     x-data="{ scrolled: false }" 
     @scroll.window="scrolled = (window.pageYOffset > 50)"
     :class="{ 'bg-black/80 backdrop-blur-md py-4': scrolled, 'bg-transparent py-6': !scrolled }">
    <div class="container mx-auto px-6 flex justify-between items-center text-white">
        <a href="{{ route('home') }}" class="text-2xl font-display font-bold tracking-tight">IVO KARYA</a>
        <div class="hidden md:flex space-x-8 text-sm font-medium tracking-wide">
            <a href="{{ route('home') }}" class="hover:text-brand-400 transition-colors">Beranda</a>
            <a href="{{ request()->routeIs('home') ? '#story' : url('/#story') }}" class="hover:text-brand-400 transition-colors">Cerita Kami</a>
            <a href="{{ route('products.index') }}" class="hover:text-brand-400 transition-colors">Produk</a>
            <a href="{{ request()->routeIs('home') ? '#articles-section' : url('/#articles-section') }}" class="hover:text-brand-400 transition-colors">Edukasi & Tips</a>
            <a href="{{ route('track.index') }}" class="hover:text-brand-400 transition-colors">Lacak Pesanan</a>
        </div>
            <!-- Icons -->
            <div class="flex items-center space-x-4" x-data="{ count: {{ count(session('cart') ?? []) }} }" @cart-updated.window="count = $event.detail.cart_count">
                 <a href="{{ route('cart.index') }}" class="relative group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white group-hover:text-brand-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span x-show="count > 0" x-text="count" class="absolute -top-2 -right-2 bg-brand-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full"></span>
                </a>
            </div>
    </div>
</nav>
