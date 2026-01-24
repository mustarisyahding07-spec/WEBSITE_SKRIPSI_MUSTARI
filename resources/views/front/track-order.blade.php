@extends('layouts.app-public')

@section('title', 'Lacak Pesanan - Ivo Karya')

@section('content')
<div class="bg-gray-50 min-h-screen pt-32 pb-20">
    <div class="container mx-auto px-6 max-w-4xl">
        
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-display font-bold text-gray-900 mb-4">Lacak Pesanan Anda</h1>
            <p class="text-gray-600">Masukkan ID Pesanan Anda untuk melihat status terbaru.</p>
        </div>

        <!-- Search Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-12 relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-brand-50 rounded-full blur-3xl opacity-50"></div>
            
            <form action="{{ route('track.index') }}" method="GET" class="relative z-10">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-grow">
                        <label for="order_id" class="block text-sm font-medium text-gray-700 mb-1">Order ID</label>
                        <input type="text" name="order_id" id="order_id" value="{{ request('order_id') }}" 
                               placeholder="Contoh: 1, 2, 3..." 
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition shadow-sm"
                               required>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full md:w-auto bg-brand-600 hover:bg-brand-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition duration-300 transform hover:-translate-y-1 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Lacak
                        </button>
                    </div>
                </div>
            </form>

            @if(session('error'))
                <div class="mt-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif
        </div>

        @if(isset($order))
        <!-- Order Result -->
        <div class="space-y-8 animate-fade-in-up">
            
            <!-- Status Timeline -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-8 flex items-center gap-2">
                    <svg class="w-6 h-6 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Status Pesanan #{{ $order->id }}
                </h2>

                <div class="relative">
                    <!-- Progress Bar -->
                    <div class="absolute left-4 top-4 bottom-4 w-0.5 bg-gray-200"></div>

                    <!-- Steps -->
                    @php
                        $statuses = ['pending', 'processing', 'shipping', 'completed'];
                        $currentStatusIndex = array_search($order->status, $statuses);
                        if ($order->status == 'cancelled') $currentStatusIndex = -1;
                    @endphp

                    <ol class="relative space-y-8 ml-4">
                        <!-- Step 1: Pending -->
                        <li class="pl-8 relative">
                            <span class="absolute -left-2 top-1 w-4 h-4 rounded-full border-2 {{ $currentStatusIndex >= 0 ? 'bg-brand-500 border-brand-500' : 'bg-white border-gray-300' }}"></span>
                            <h3 class="font-bold {{ $currentStatusIndex >= 0 ? 'text-brand-600' : 'text-gray-500' }}">Menunggu Pembayaran</h3>
                            <p class="text-sm text-gray-500">Pesanan telah dibuat dan menunggu konfirmasi pembayaran.</p>
                            @if($currentStatusIndex >= 0)
                                <span class="text-xs text-brand-400 mt-1 block">{{ $order->created_at->format('d M Y, H:i') }}</span>
                            @endif
                        </li>

                        <!-- Step 2: Processing -->
                        <li class="pl-8 relative">
                            <span class="absolute -left-2 top-1 w-4 h-4 rounded-full border-2 {{ $currentStatusIndex >= 1 ? 'bg-brand-500 border-brand-500' : 'bg-white border-gray-300' }}"></span>
                            <h3 class="font-bold {{ $currentStatusIndex >= 1 ? 'text-brand-600' : 'text-gray-500' }}">Sedang Diproses</h3>
                            <p class="text-sm text-gray-500">Pesanan Anda sedang disiapkan dan dikemas.</p>
                        </li>

                        <!-- Step 3: Shipping -->
                        <li class="pl-8 relative">
                            <span class="absolute -left-2 top-1 w-4 h-4 rounded-full border-2 {{ $currentStatusIndex >= 2 ? 'bg-brand-500 border-brand-500' : 'bg-white border-gray-300' }}"></span>
                            <h3 class="font-bold {{ $currentStatusIndex >= 2 ? 'text-brand-600' : 'text-gray-500' }}">Dalam Pengiriman</h3>
                            <p class="text-sm text-gray-500">Pesanan dalam perjalanan ke alamat tujuan.</p>
                            @if($order->status == 'shipping' && $order->tracking_number)
                                <div class="mt-2 bg-blue-50 border border-blue-200 p-3 rounded text-sm text-blue-700">
                                    Resi: <strong>{{ $order->tracking_number }}</strong>
                                </div>
                            @endif
                        </li>

                        <!-- Step 4: Completed -->
                        <li class="pl-8 relative">
                            <span class="absolute -left-2 top-1 w-4 h-4 rounded-full border-2 {{ $currentStatusIndex >= 3 ? 'bg-green-500 border-green-500' : 'bg-white border-gray-300' }}"></span>
                            <h3 class="font-bold {{ $currentStatusIndex >= 3 ? 'text-green-600' : 'text-gray-500' }}">Selesai</h3>
                            <p class="text-sm text-gray-500">Pesanan telah diterima.</p>
                        </li>
                    </ol>
                    
                    @if($order->status == 'cancelled')
                        <div class="mt-8 ml-4 pl-8 relative">
                             <span class="absolute -left-2 top-1 w-4 h-4 rounded-full border-2 bg-red-500 border-red-500"></span>
                             <h3 class="font-bold text-red-600">Dibatalkan</h3>
                             <p class="text-sm text-gray-500">Pesanan ini telah dibatalkan.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Details -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Detail Pesanan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Informasi Pengiriman</h4>
                        <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-700 space-y-2">
                            <p><span class="font-bold">Penerima:</span> {{ $order->customer_name }}</p>
                            <p><span class="font-bold">Telepon:</span> {{ $order->customer_phone }}</p>
                            <p><span class="font-bold">Alamat:</span><br>{{ $order->customer_address }}</p>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Ringkasan Produk</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <ul class="divide-y divide-gray-200">
                                @if($order->items_json)
                                    @foreach($order->items_json as $item)
                                    <li class="py-2 flex justify-between text-sm">
                                        <span class="text-gray-700">{{ $item['name'] }} <span class="text-gray-400">x{{ $item['quantity'] }}</span></span>
                                        <span class="font-medium">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                    </li>
                                    @endforeach
                                @endif
                                <li class="pt-3 flex justify-between font-bold text-lg border-t border-gray-300 mt-2">
                                    <span>Total</span>
                                    <span class="text-brand-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        @endif

    </div>
</div>
@endsection
