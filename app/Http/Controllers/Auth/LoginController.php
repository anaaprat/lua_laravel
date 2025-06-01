<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            Log::info('Usuario intentando acceder a web', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'is_active' => $user->is_active,
                'deleted' => $user->deleted
            ]);

            // 1. Verificar si el usuario tiene rol 'user'
            if ($user->role === 'user') {
                Auth::logout();
                Log::info('Acceso denegado: usuario con rol cliente', ['user_id' => $user->id]);
                return back()->with('error', 'Esta plataforma web es solo para administradores y bares. Los clientes deben usar la aplicación móvil.');
            }

            // 2. Verificar si el usuario está eliminado
            if ($user->deleted) {
                Auth::logout();
                Log::warning('Acceso denegado: usuario eliminado', ['user_id' => $user->id]);
                return back()->with('error', 'Tu cuenta ha sido eliminada. Contacta con el administrador si crees que es un error.');
            }

            // 3. Verificar si el usuario está inactivo
            if (!$user->is_active) {
                Auth::logout();
                Log::warning('Acceso denegado: usuario inactivo', ['user_id' => $user->id]);

                $message = match ($user->role) {
                    'bar' => 'Tu bar está temporalmente desactivado. Contacta con el administrador para reactivar tu cuenta.',
                    'admin' => 'Tu cuenta de administrador está desactivada. Contacta con el administrador principal.',
                    default => 'Tu cuenta está desactivada. Contacta con el administrador.'
                };

                return back()->with('error', $message);
            }

            // 4. Si todo está bien, regenerar sesión y redirigir
            $request->session()->regenerate();

            Log::info('Login exitoso', [
                'user_id' => $user->id,
                'role' => $user->role,
                'redirect_to' => $user->role === 'admin' ? '/admin' : '/bar'
            ]);

            // Redirigir según el rol (solo admin y bar activos)
            return match ($user->role) {
                'admin' => redirect()->intended('/admin')->with('success', "Bienvenido, {$user->name}"),
                'bar' => redirect()->intended('/bar')->with('success', "Bienvenido al panel de {$user->name}"),
                default => redirect()->intended('/')->with('info', 'Acceso concedido'),
            };
        }

        // Credenciales incorrectas
        Log::warning('Intento de login fallido', [
            'email' => $request->email,
            'ip' => $request->ip()
        ]);

        return back()->with('error', 'Credenciales incorrectas. Verifica tu email y contraseña.');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            Log::info('Usuario cerrando sesión', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('info', 'Has cerrado sesión correctamente');
    }
}