<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class BarController extends Controller
{
    public function dashboard()
    {

        $today = Carbon::today();

        $orders = Order::with(['user', 'items.product'])
            ->where('bar_id', auth()->id())->whereDate('created_at', $today)
            ->orderByRaw("FIELD(status, 'pending', 'completed', 'canceled')")
            ->get();

        $pendingOrders = $orders->where('status', 'pending')->sortBy('created_at');
        $completedOrders = $orders->where('status', 'completed')->sortByDesc('created_at');

        return view('bar.dashboard', compact('pendingOrders', 'completedOrders'));
    }

    public function getOrdersAjax()
    {
        $today = Carbon::today();

        $orders = Order::with(['user', 'items.product'])
            ->where('bar_id', auth()->id())
            ->whereDate('created_at', $today)
            ->orderByRaw("FIELD(status, 'pending', 'completed', 'canceled')")
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingOrders = $orders->where('status', 'pending')->sortBy('created_at');
        $completedOrders = $orders->where('status', 'completed')->sortByDesc('updated_at');

        $pendingOrdersHtml = view('bar.partials.pending-orders', compact('pendingOrders'))->render();
        $completedOrdersHtml = view('bar.partials.completed-orders', compact('completedOrders'))->render();

        return response()->json([
            'pending_count' => $pendingOrders->count(),
            'completed_count' => $completedOrders->count(),
            'total_sales' => number_format($pendingOrders->sum('total') + $completedOrders->sum('total'), 2),
            'pending_orders_html' => $pendingOrdersHtml,
            'completed_orders_html' => $completedOrdersHtml,
            'timestamp' => now()->format('H:i:s')
        ]);
    }
}