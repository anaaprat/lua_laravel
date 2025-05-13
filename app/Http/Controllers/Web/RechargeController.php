<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movement;
use Illuminate\Support\Facades\Auth;

class RechargeController extends Controller
{
    /**
     * Display the recharges page
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Get authenticated bar
        $bar = Auth::user();

        // Build query to get movements (recharges) for the current bar
        $movementsQuery = Movement::where('bar_id', $bar->id)
            ->with('user'); // Eager load user data

        // Apply date filters if provided
        if ($request->filled('from')) {
            $movementsQuery->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $movementsQuery->whereDate('created_at', '<=', $request->to);
        }

        // Get movements ordered by most recent first
        $movements = $movementsQuery->orderBy('created_at', 'desc')->get();

        // Calculate total recharge amount
        $totalAmount = $movements->sum('amount');

        // Calculate positive (deposits) and negative (withdrawals) totals
        $deposits = $movements->where('amount', '>', 0)->sum('amount');
        $withdrawals = $movements->where('amount', '<', 0)->sum('amount') * -1; // Make positive for display

        return view('bar.recharges', compact('movements', 'totalAmount', 'deposits', 'withdrawals'));
    }
}