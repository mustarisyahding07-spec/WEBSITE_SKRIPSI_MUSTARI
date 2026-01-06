@extends('layouts.app-public')

@section('title', 'Lacak Pesanan #' . $order->id . ' - Ivo Karya')

@section('content')
@section('content')
<div class="relative bg-black pt-32 pb-20 overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('img/premium/process.jpg') }}" alt="Background" class="w-full h-full object-cover opacity-30">
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-gray-50"></div>
    </div>
    <div class="container mx-auto px-6 relative z-10 text-center">
        <span class="inline-block py-1 px-3 rounded-full bg-brand-500/20 text-brand-300 border border-brand-500/30 text-xs font-bold tracking-wider mb-4 uppercase">Status Pesanan</span>
        <h1 class="text-3xl md:text-4xl font-display font-bold text-white mb-2">Terima Kasih, {{ $order->customer_name }}!</h1>
        <p class="text-gray-300">Pesanan Anda telah kami terima. Silakan cek status berkala di halaman ini.</p>
    </div>
</div>

<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-6 max-w-3xl">

        <!-- Status Card -->
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden mb-8">
            <div class="p-8 border-b border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <p class="text-sm text-gray-400">Order ID</p>
                        <p class="text-xl font-bold font-display text-gray-900">#{{ $order->id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400 text-right">Total</p>
                        <p class="text-xl font-bold font-display text-brand-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Stepper -->
                <div class="relative">
                    <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-gray-100 rounded-full -z-10"></div>
                    <div class="absolute left-0 top-1/2 transform -translate-y-1/2 h-1 bg-brand-500 rounded-full -z-10 transition-all duration-1000" style="width: {{ $order->status == 'pending' ? '10%' : ($order->status == 'processing' ? '50%' : ($order->status == 'shipped' ? '80%' : '100%')) }}"></div>
                    
                    <div class="flex justify-between">
                        <!-- Step 1: Pending -->
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $order->status == 'pending' || $order->status == 'processing' || $order->status == 'shipped' || $order->status == 'completed' ? 'bg-brand-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <span class="text-xs font-bold mt-2 text-gray-900">Diterima</span>
                        </div>
                        
                        <!-- Step 2: Processing -->
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $order->status == 'processing' || $order->status == 'shipped' || $order->status == 'completed' ? 'bg-brand-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                            </div>
                            <span class="text-xs font-bold mt-2 {{ in_array($order->status, ['processing', 'shipped', 'completed']) ? 'text-gray-900' : 'text-gray-400' }}">Diproses</span>
                        </div>

                        <!-- Step 3: Shipped -->
                        <div class="flex flex-col items-center">
                             <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $order->status == 'shipped' || $order->status == 'completed' ? 'bg-brand-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" /></svg>
                            </div>
                            <span class="text-xs font-bold mt-2 {{ in_array($order->status, ['shipped', 'completed']) ? 'text-gray-900' : 'text-gray-400' }}">Dikirim</span>
                        </div>

                        <!-- Step 4: Completed -->
                        <div class="flex flex-col items-center">
                             <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $order->status == 'completed' ? 'bg-brand-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <span class="text-xs font-bold mt-2 {{ $order->status == 'completed' ? 'text-gray-900' : 'text-gray-400' }}">Selesai</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-8 bg-gray-50/50">
                <h3 class="font-bold text-gray-900 mb-4">Detail Item</h3>
                <ul class="space-y-3">
                    @foreach($order->items_json as $item)
                    <li class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ $item['quantity'] }}x {{ $item['name'] }}</span>
                        <span class="font-medium">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                    </li>
                    @endforeach
                </ul>
                
                @if($order->tracking_number)
                <div class="mt-6 p-4 bg-blue-50 text-blue-800 rounded-xl flex items-center justify-between">
                    <span class="text-sm font-bold">Resi Pengiriman:</span>
                    <span class="font-mono text-lg select-all">{{ $order->tracking_number }}</span>
                </div>
                @endif
            </div>
            
            @if($order->status == 'shipped')
            <div class="p-4 bg-white border-t border-gray-100 text-center">
                 <form action="{{ route('order.confirm', $order->tracking_token) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-xl transition-colors shadow-lg">
                        Konfirmasi Pesanan Diterima
                    </button>
                    <p class="text-xs text-gray-400 mt-2">Klik jika barang sudah sampai dengan aman.</p>
                 </form>
            </div>
            @endif
        </div>

        <div class="text-center">
            <a href="{{ route('home') }}" class="text-brand-600 font-bold hover:underline">Kembali ke Beranda</a>
        </div>

    </div>
</div>
@endsection
