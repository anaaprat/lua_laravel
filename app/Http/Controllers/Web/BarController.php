<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class BarController extends Controller
{
    public function dashboard()
    {
        $orders = Order::where('bar_id', Auth::id())->get(); 

        return view('bar.dashboard', compact('orders'));
    }
}