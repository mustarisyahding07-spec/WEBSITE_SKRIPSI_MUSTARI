<div x-data="{ show: false, message: '', cartCount: 0 }" 
     @cart-updated.window="show = true; message = $event.detail.message; cartCount = $event.detail.cart_count; setTimeout(() => show = false, 4000)"
     class="fixed inset-0 z-[100] flex items-center justify-center pointer-events-none"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 backdrop-blur-none"
     x-transition:enter-end="opacity-100 backdrop-blur-sm"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 backdrop-blur-sm"
     x-transition:leave-end="opacity-0 backdrop-blur-none"
     style="background-color: rgba(0,0,0,0.3);">

    <div class="bg-white w-full max-w-md p-6 rounded-3xl shadow-2xl transform transition-all pointer-events-auto border border-white/20"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-8 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-8 scale-95">
        
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            
            <h3 class="text-2xl font-display font-bold text-gray-900 mb-2">Berhasil!</h3>
            <p class="text-gray-500 mb-8" x-text="message"></p>
            
            <div class="grid grid-cols-2 gap-4">
                <button @click="show = false" class="w-full py-3 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-bold transition-colors">
                    Lanjut Belanja
                </button>
                <a href="{{ route('cart.index') }}" class="w-full py-3 px-4 bg-black hover:bg-gray-800 text-white rounded-xl font-bold transition-colors flex items-center justify-center gap-2">
                    Lihat Cart
                </a>
            </div>
        </div>
    </div>
</div>
