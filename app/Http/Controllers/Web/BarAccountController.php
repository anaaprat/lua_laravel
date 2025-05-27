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
    /**
     * Mostrar la página de cuenta del bar
     */
    public function show()
    {
        $bar = Auth::user();
        return view('bar.account', compact('bar'));
    }

    /**
     * Actualizar la información del bar
     */
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
        
        // Si el nombre cambió, regenerar el código QR
        if ($bar->isDirty('name')) {
            // Generar nuevo QR con el token existente
            $qr = QrCode::format('svg')->size(300)->generate($bar->token);
            $filePath = 'qrs/bar_' . $validated['name'] . '.svg';
            
            // Eliminar el anterior si existe
            if ($bar->qr_path) {
                Storage::disk('public')->delete($bar->qr_path);
            }
            
            // Guardar el nuevo
            Storage::disk('public')->put($filePath, $qr);
            $bar->qr_path = $filePath;
        }
        
        $bar->save();
        
        return redirect()->route('bar.account')->with('success', 'Información actualizada correctamente');
    }

    /**
     * Actualizar la contraseña del bar
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);
        
        $bar = Auth::user();
        
        // Verificar contraseña actual
        if (!Hash::check($validated['current_password'], $bar->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual no es correcta']);
        }
        
        // Actualizar contraseña
        $bar->password = Hash::make($validated['password']);
        $bar->save();
        
        return redirect()->route('bar.account')->with('success', 'Contraseña actualizada correctamente');
    }
}