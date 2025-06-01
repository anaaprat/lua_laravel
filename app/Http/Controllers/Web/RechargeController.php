<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movement;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    /**
     * Display the user recharges page
     *
     * @return \Illuminate\View\View
     */
    public function rechargesUser(Request $request)
    {
        $bar = Auth::user();

        $user = null;
        if ($request->filled('search')) {
            $search = $request->input('search');
            $user = User::where('role', 'user')
                ->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                ->where('is_active', 1)
                ->where('deleted', 0)
                ->first();
        }

        $recentRecharges = Movement::where('bar_id', $bar->id)
            ->where('amount', '>', 0)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->take(10) // Limit to 10 most recent
            ->get();

        return view('bar.rechargesUser', compact('user', 'recentRecharges'));
    }

    /**
     * Add credit to a user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addCredit(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $barId = Auth::id();
        $userId = $request->input('user_id');
        $amount = $request->input('amount');

        // Find the user
        $user = User::findOrFail($userId);

        // Verify user is active and not deleted
        if (!$user->is_active || $user->deleted) {
            return redirect()->back()->with('error', 'No se puede añadir crédito a este usuario.');
        }

        // Verify user has role 'user'
        if ($user->role !== 'user') {
            return redirect()->back()->with('error', 'Solo se puede añadir crédito a usuarios.');
        }

        try {
            DB::beginTransaction();

            // Create a movement record
            Movement::create([
                'user_id' => $userId,
                'bar_id' => $barId,
                'amount' => $amount,
            ]);

            // Update user's credit
            $user->credit += $amount;
            $user->save();

            DB::commit();

            return redirect()->route('bar.rechargesUser', ['search' => $user->name])
                ->with('success', "Se han añadido {$amount}€ al crédito de {$user->name}. Nuevo saldo: {$user->credit}€");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al procesar la recarga: ' . $e->getMessage());
        }
    }
}