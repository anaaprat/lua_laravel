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
        $bar = Auth::user();

        $movementsQuery = Movement::where('bar_id', $bar->id)
            ->with('user');

        if ($request->filled('from')) {
            $movementsQuery->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $movementsQuery->whereDate('created_at', '<=', $request->to);
        }

        $movements = $movementsQuery->orderBy('created_at', 'desc')->get();

        $totalAmount = $movements->sum('amount');

        $deposits = $movements->where('amount', '>', 0)->sum('amount');
        $withdrawals = $movements->where('amount', '<', 0)->sum('amount') * -1; 

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
            ->take(10) 
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

        $user = User::findOrFail($userId);

        if (!$user->is_active || $user->deleted) {
            return redirect()->back()->with('error', 'No se puede añadir crédito a este usuario.');
        }

        if ($user->role !== 'user') {
            return redirect()->back()->with('error', 'Solo se puede añadir crédito a usuarios.');
        }

        try {
            DB::beginTransaction();

            Movement::create([
                'user_id' => $userId,
                'bar_id' => $barId,
                'amount' => $amount,
            ]);

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