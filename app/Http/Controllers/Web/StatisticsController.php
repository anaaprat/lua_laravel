<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $barId = Auth::id();

        $orders = Order::with('user', 'items.product')
            ->where('bar_id', $barId) 
            ->when($request->from, fn($q) => $q->whereDate('created_at', '>=', $request->from))
            ->when($request->to, fn($q) => $q->whereDate('created_at', '<=', $request->to))
            ->orderByDesc('created_at')
            ->get();

        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(subtotal) as total_sales'))
            ->whereHas('order', function ($query) use ($barId) {
                $query->where('bar_id', $barId); 
            })
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->with('product')
            ->take(10)
            ->get();

        $totalSales = $orders->sum('total');
        $completedOrders = $orders->where('status', 'completed')->count();
        $pendingOrders = $orders->where('status', 'pending')->count();

        return view('bar.statistics', compact('orders', 'topProducts', 'totalSales', 'completedOrders', 'pendingOrders'));
    }
}