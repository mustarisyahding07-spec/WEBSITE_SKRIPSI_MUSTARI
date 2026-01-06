@extends('layouts.app-public')

@section('title', 'Keranjang Belanja - Ivo Karya')

@section('content')
<div class="relative bg-black pt-32 pb-20 overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('img/premium/texture.jpg') }}" alt="Background" class="w-full h-full object-cover opacity-30">
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-gray-50"></div>
    </div>
    <div class="container mx-auto px-6 relative z-10 text-center">
        <h1 class="text-4xl md:text-5xl font-display font-bold text-white mb-4">Keranjang Belanja</h1>
        <p class="text-gray-300">Selesaikan pesanan Anda untuk menikmati kelezatan otentik.</p>
    </div>
</div>

<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-6">

        @if(session('success'))
            <div class="max-w-4xl mx-auto mb-8 p-4 bg-green-50 border border-green-100 text-green-700 rounded-2xl flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="max-w-4xl mx-auto mb-8 p-4 bg-red-50 border border-red-100 text-red-700 rounded-2xl flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(session('cart') && count(session('cart')) > 0)
            <div class="flex flex-col lg:flex-row gap-12 max-w-7xl mx-auto">
                <!-- Cart Items List -->
                <div class="lg:w-2/3">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-gray-50 border-b border-gray-100">
                                    <tr>
                                        <th class="px-8 py-6 text-xs font-bold text-gray-400 uppercase tracking-wider">Produk</th>
                                        <th class="px-6 py-6 text-xs font-bold text-gray-400 uppercase tracking-wider">Harga</th>
                                        <th class="px-6 py-6 text-xs font-bold text-gray-400 uppercase tracking-wider">Qty</th>
                                        <th class="px-6 py-6 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Total</th>
                                        <th class="px-6 py-6"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @php $total = 0 @endphp
                                    @foreach(session('cart') as $id => $details)
                                        @php $total += $details['price'] * $details['quantity'] @endphp
                                        <tr class="group hover:bg-gray-50/50 transition-colors">
                                            <td class="px-8 py-6">
                                                <div class="flex items-center gap-6">
                                                    <div class="h-20 w-20 rounded-xl overflow-hidden bg-gray-100 shadow-sm shrink-0">
                                                         @if($details['image'])
                                                            <img src="{{ Str::startsWith($details['image'], 'http') ? $details['image'] : Storage::url($details['image']) }}" class="w-full h-full object-cover">
                                                         @else
                                                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                            </div>
                                                         @endif
                                                    </div>
                                                    <div>
                                                        <a href="#" class="font-display font-bold text-lg text-gray-900 hover:text-brand-600 transition-colors">{{ $details['name'] }}</a>
                                                        <p class="text-sm text-gray-500 mt-1">Premium Quality</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-6 text-gray-600 font-medium">
                                                Rp {{ number_format($details['price'], 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-6">
                                                <div class="inline-flex items-center bg-gray-100 rounded-lg px-2 py-1">
                                                    <span class="text-gray-900 font-bold px-2">{{ $details['quantity'] }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-6 text-right font-display font-bold text-gray-900">
                                                Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-6 text-right">
                                                <form action="{{ route('cart.remove', $id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors p-2 rounded-full hover:bg-red-50">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-between items-center px-2">
                        <a href="{{ route('home') }}#shop-section" class="flex items-center gap-2 text-gray-500 hover:text-gray-900 font-medium transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                            </svg>
                            Lanjut Belanja
                        </a>
                    </div>
                </div>

                <!-- Checkout Form -->
                <div class="lg:w-1/3">
                    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 sticky top-28">
                        <h2 class="font-display font-bold text-2xl text-gray-900 mb-6">Ringkasan Pesanan</h2>
                        
                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal</span>
                                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-brand-600 font-bold text-xl pt-4 border-t border-gray-100">
                                <span>Total</span>
                                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <form action="{{ route('cart.checkout') }}" method="POST" class="space-y-5">
                            @csrf
                            <div>
                                <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="name" id="name" placeholder="Masukkan nama Anda" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3.5 transition-all outline-none" required>
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-bold text-gray-700 mb-2">No. WhatsApp</label>
                                <input type="tel" name="phone" id="phone" placeholder="Contoh: 081234567890" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3.5 transition-all outline-none" required>
                                <p class="mt-1.5 text-xs text-gray-500">Kami akan mengirim detail pembayaran ke nomor ini.</p>
                            </div>
                            <div>
                                <label for="address" class="block text-sm font-bold text-gray-700 mb-2">Alamat Pengiriman</label>
                                <textarea name="address" id="address" rows="3" placeholder="Alamat lengkap..." class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3.5 transition-all outline-none" required></textarea>
                            </div>
                            
                            <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white font-bold rounded-xl text-lg px-5 py-4 text-center inline-flex items-center justify-center gap-3 transition-colors duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                Pesan via WhatsApp
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-20">
                <div class="h-32 w-32 bg-gray-100 rounded-full flex items-center justify-center mb-6 text-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Keranjang Anda Kosong</h2>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">Sepertinya Anda belum menemukan produk favorit. Mari jelajahi koleksi lezat kami.</p>
                <a href="{{ route('home') }}#shop-section" class="inline-flex items-center px-8 py-4 bg-gray-900 text-white font-bold rounded-xl hover:bg-brand-600 transition-colors shadow-lg">
                    Mulai Belanja
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
