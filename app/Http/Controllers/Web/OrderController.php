<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function markAsCompleted(Order $order)
    {
        if ($order->bar_id !== auth()->id())
            abort(403);
        $order->status = 'completed';
        $order->save();
        return back();
    }

    public function markAsPending(Order $order)
    {
        if ($order->bar_id !== auth()->id())
            abort(403);
        $order->status = 'pending';
        $order->save();
        return back();
    }

    public function cancel(Order $order)
    {
        if ($order->bar_id !== auth()->id())
            abort(403);
        $order->status = 'canceled';
        $order->save();
        return back();
    }
}