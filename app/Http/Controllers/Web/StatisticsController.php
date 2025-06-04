<?php

namespace App\Http\Controllers\Web;

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

        // Obtener top products primero (sin paginación)
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(subtotal) as total_sales'))
            ->whereHas('order', function ($query) use ($barId, $request) {
                $query->where('bar_id', $barId);

                // Aplicar filtros de fecha si existen
                if ($request->from) {
                    $query->whereDate('created_at', '>=', $request->from);
                }
                if ($request->to) {
                    $query->whereDate('created_at', '<=', $request->to);
                }
            })
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->with('product')
            ->take(10)
            ->get();

        // Obtener pedidos con paginación
        $orders = Order::with('user', 'items.product')
            ->where('bar_id', $barId)
            ->when($request->from, fn($q) => $q->whereDate('created_at', '>=', $request->from))
            ->when($request->to, fn($q) => $q->whereDate('created_at', '<=', $request->to))
            ->orderByDesc('created_at')
            ->paginate(15); // 15 pedidos por página

        // Calcular estadísticas basadas en todos los pedidos (sin paginación)
        $allOrdersForStats = Order::where('bar_id', $barId)
            ->when($request->from, fn($q) => $q->whereDate('created_at', '>=', $request->from))
            ->when($request->to, fn($q) => $q->whereDate('created_at', '<=', $request->to))
            ->get();

        $totalSales = $allOrdersForStats->sum('total');
        $completedOrders = $allOrdersForStats->where('status', 'completed')->count();
        $pendingOrders = $allOrdersForStats->where('status', 'pending')->count();

        return view('bar.statistics', compact('orders', 'topProducts', 'totalSales', 'completedOrders', 'pendingOrders'));
    }
}