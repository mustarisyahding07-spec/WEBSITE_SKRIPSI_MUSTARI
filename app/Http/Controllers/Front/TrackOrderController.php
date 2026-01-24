<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class TrackOrderController extends Controller
{
    public function index(Request $request)
    {
        $order = null;
        
        if ($request->has('order_id')) {
            $order = Order::find($request->order_id);
            
            if (!$order) {
                return back()->with('error', 'Pesanan dengan ID tersebut tidak ditemukan.');
            }
        }

        return view('front.track-order', compact('order'));
    }
}
