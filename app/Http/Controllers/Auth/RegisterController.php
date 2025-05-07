<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        // Generar token único
        $token = Str::uuid();

        // Crear usuario con rol bar
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'bar',
            'token' => $token,
        ]);

        $qr = QrCode::format('svg')->size(300)->generate($token);
        $filePath = 'qrs/bar_' . $user->name . '.svg';
        Storage::disk('public')->put($filePath, $qr);

        $user->qr_path = $filePath;
        $user->save();

        // Autologin y redirigir
        auth()->login($user);
        return redirect()->route('bar.dashboard');
    }
}
