<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    /**
     * Mostrar el formulario para solicitar reset de contraseña
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Enviar el link de reset por email
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El formato del email no es válido.',
            'email.exists' => 'No encontramos una cuenta con este email.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'No encontramos una cuenta con este email.',
            ]);
        }

        if ($user->role === 'user') {
            throw ValidationException::withMessages([
                'email' => 'Esta función es solo para administradores y bares. Los clientes deben usar la aplicación móvil.',
            ]);
        }

        if (!in_array($user->role, ['admin', 'bar'])) {
            throw ValidationException::withMessages([
                'email' => 'No tienes permisos para usar esta función.',
            ]);
        }

        if (!$user->is_active || $user->deleted) {
            throw ValidationException::withMessages([
                'email' => 'Esta cuenta no está activa.',
            ]);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Te hemos enviado un enlace para restablecer tu contraseña por email.');
        }

        throw ValidationException::withMessages([
            'email' => 'Ha ocurrido un error al enviar el email. Inténtalo de nuevo.',
        ]);
    }
}