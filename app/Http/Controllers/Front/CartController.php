<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }
        return view('front.cart', compact('cart', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        $cart = session()->get('cart', []);
        $quantity = $request->input('quantity', 1);

        if(isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = [
                "name" => $product->name,
                "quantity" => $quantity,
                "price" => $product->price,
                "image" => $product->image,
                "weight" => $product->weight
            ];
        }

        session()->put('cart', $cart);
        
        // Return JSON if AJAX
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Produk berhasil ditambahkan ke keranjang!', 'cart_count' => count($cart)]);
        }

        // Redirect to cart if "Buy Now"
        if ($request->input('action') === 'buy_now') {
            return redirect()->route('cart.index');
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request)
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            session()->flash('success', 'Keranjang berhasil diperbarui');
        }
    }

    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            session()->flash('success', 'Produk berhasil dihapus dari keranjang');
        }
        return redirect()->back();
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'name' => 'required|string',
        ]);

        $cart = session()->get('cart');
        if(!$cart || count($cart) == 0) {
            return redirect()->back()->with('error', 'Keranjang belanja kosong');
        }

        // 1. Format Message
        $message = "Halo Admin Ivo Karya, saya ingin memesan:\n\n";
        $total = 0;
        
        foreach($cart as $id => $details) {
            $subtotal = $details['price'] * $details['quantity'];
            $total += $subtotal;
            $message .= "- {$details['name']} ({$details['quantity']}x) - Rp " . number_format($subtotal, 0, ',', '.') . "\n";
        }
        
        $message .= "\nTotal: Rp " . number_format($total, 0, ',', '.') . "\n";
        $message .= "\nNama: " . $request->name;
        $message .= "\nAlamat Pengiriman: " . $request->address;
        
        // 2. Clear Cart (Optional? Maybe keep until confirmed? Let's clear for now as it redirects)
        // session()->forget('cart'); 

        // 3. Save Order to Database (As per requirements "Order Monitoring")
        $order = null;
        try {
            $order = \App\Models\Order::create([
                'user_id' => auth()->id(), // Null if guest
                'customer_name' => $request->name,
                'customer_address' => $request->address,
                'customer_phone' => $request->phone, 
                'items_json' => $cart,
                'total_amount' => $total,
                'total_weight' => 0, // Calculate if needed based on items
                'status' => 'pending',
                'whatsapp_ref' => 'WA-' . time(),
            ]);
            
            // Clear Cart after successful DB save
            session()->forget('cart');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        // 4. Redirect to Tracking Page
        return redirect()->route('order.track', $order->tracking_token);
    }

    public function track($token)
    {
        $order = \App\Models\Order::where('tracking_token', $token)->firstOrFail();
        return view('front.track', compact('order'));
    }

    public function confirmReceive($token)
    {
        $order = \App\Models\Order::where('tracking_token', $token)->firstOrFail();
        
        if($order->status == 'shipped') {
            $order->update(['status' => 'completed']);
        }
        
        return redirect()->back()->with('success', 'Terima kasih! Pesanan telah dikonfirmasi selesai.');
    }
}
