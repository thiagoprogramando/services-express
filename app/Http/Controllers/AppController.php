<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppController extends Controller {
    
    public function index() {

        $orders = Order::where('user_id', Auth::user()->id)->get();
        $prices = Price::where('user_id', Auth::user()->id)->get();

        $approvedCount  = $orders->where('status', 1)->count();
        $pendingCount   = $orders->where('status', '!=', 1)->count();

        return view('app.app', [
            'orders'            => $orders,
            'prices'            => $prices,
            'approvedCount'     => $approvedCount,
            'pendingCount'      => $pendingCount,
        ]);
    }
}
