<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BarAccountController extends Controller
{
    public function show()
    {
        $bar = Auth::user();
        return view('bar.account', compact('bar'));
    }

    public function update(Request $request)
    {
        $bar = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($bar->id)],
            'table_number' => 'required|integer|min:1|max:100',
        ]);
        
        $bar->name = $validated['name'];
        $bar->email = $validated['email'];
        $bar->table_number = $validated['table_number'];
        
        if ($bar->isDirty('name')) {
            $qr = QrCode::format('svg')->size(300)->generate($bar->token);
            $filePath = 'qrs/bar_' . $validated['name'] . '.svg';
            
            if ($bar->qr_path) {
                Storage::disk('public')->delete($bar->qr_path);
            }
            
            Storage::disk('public')->put($filePath, $qr);
            $bar->qr_path = $filePath;
        }
        
        $bar->save();
        
        return redirect()->route('bar.account')->with('success', 'Información actualizada correctamente');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);
        
        $bar = Auth::user();
        
        if (!Hash::check($validated['current_password'], $bar->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual no es correcta']);
        }
        
        $bar->password = Hash::make($validated['password']);
        $bar->save();
        
        return redirect()->route('bar.account')->with('success', 'Contraseña actualizada correctamente');
    }
}