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
        // Obtener el ID del bar autenticado
        $barId = Auth::id();

        // Historial filtrado por bar actual y fecha
        $orders = Order::with('user', 'items.product')
            ->where('bar_id', $barId) // Filtro para mostrar solo órdenes del bar actual
            ->when($request->from, fn($q) => $q->whereDate('created_at', '>=', $request->from))
            ->when($request->to, fn($q) => $q->whereDate('created_at', '<=', $request->to))
            ->orderByDesc('created_at')
            ->get();

        // Top productos por cantidad (solo de este bar)
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(subtotal) as total_sales'))
            ->whereHas('order', function ($query) use ($barId) {
                $query->where('bar_id', $barId); // Filtro de bar para los items de orden
            })
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->with('product')
            ->take(10)
            ->get();

        // Calculamos algunas estadísticas adicionales
        $totalSales = $orders->sum('total');
        $completedOrders = $orders->where('status', 'completed')->count();
        $pendingOrders = $orders->where('status', 'pending')->count();

        return view('bar.statistics', compact('orders', 'topProducts', 'totalSales', 'completedOrders', 'pendingOrders'));
    }
}