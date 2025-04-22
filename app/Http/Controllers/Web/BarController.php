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
        $orders = Order::with(['user', 'items.product'])
            ->where('bar_id', auth()->id())
            ->orderByRaw("FIELD(status, 'pending', 'completed', 'canceled')")
            ->get();

        $pendingOrders = $orders->where('status', 'pending')->sortBy('created_at');
        $completedOrders = $orders->where('status', 'completed')->sortByDesc('created_at');

        return view('bar.dashboard', compact('pendingOrders', 'completedOrders'));
    }
}