<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ranking;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RankingController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $user = $request->user();
        
        $code = strtoupper(Str::random(6));
        
        $ranking = Ranking::create([
            'name' => $request->name,
            'code' => $code,
            'creator_id' => $user->id,
        ]);
        
        $ranking->users()->attach($user->id, ['points' => 0, 'month_record' => 0]);
        
        return response()->json([
            'message' => 'Ranking creado correctamente',
            'ranking' => $ranking,
        ], 201);
    }
    
    public function join(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|exists:rankings,code',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $user = $request->user();
        $ranking = Ranking::where('code', $request->code)->first();
        
        $isMember = $ranking->users()->where('user_id', $user->id)->exists();
        
        if ($isMember) {
            return response()->json([
                'message' => 'Ya eres miembro de este ranking',
            ], 422);
        }
        
        $ranking->users()->attach($user->id, ['points' => 0, 'month_record' => 0]);
        
        return response()->json([
            'message' => 'Te has unido al ranking correctamente',
            'ranking' => $ranking->load('users:id,name'),
        ]);
    }
    
    public function myRankings(Request $request)
    {
        $user = $request->user();
        
        $rankings = $user->rankings()->with(['users' => function($query) {
            $query->select('users.id', 'users.name', 'ranking_users.points', 'ranking_users.month_record')
                 ->orderBy('ranking_users.points', 'desc');
        }])->get();
        
        return response()->json([
            'rankings' => $rankings,
        ]);
    }
    
    public function show(Request $request, $rankingId)
    {
        $user = $request->user();
        
        $ranking = Ranking::with(['users' => function($query) {
            $query->select('users.id', 'users.name', 'ranking_users.points', 'ranking_users.month_record')
                 ->orderBy('ranking_users.points', 'desc');
        }])->findOrFail($rankingId);
        
        $isMember = $ranking->users()->where('user_id', $user->id)->exists();
        
        if (!$isMember) {
            return response()->json([
                'message' => 'No tienes acceso a este ranking',
            ], 403);
        }
        
        return response()->json([
            'ranking' => $ranking,
        ]);
    }
    
    public function resetMonthly()
    {
        DB::table('ranking_users')
          ->where('points', '>', DB::raw('month_record'))
          ->update(['month_record' => DB::raw('points')]);
          
        DB::table('ranking_users')->update(['points' => 0]);
        
        return response()->json([
            'message' => 'Rankings mensuales reiniciados correctamente',
        ]);
    }
}