<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Exception;
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
            'table_number' => 'required|integer|min:1|max:100',
        ]);

        $token = Str::uuid()->toString();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'bar',
            'token' => $token,
            'table_number' => $validated['table_number'],
        ]);

        try {
            // Crear directorio qrs si no existe
            $qrDir = storage_path('app/public/qrs');
            if (!is_dir($qrDir)) {
                mkdir($qrDir, 0755, true);
                error_log("Created qrs directory: " . $qrDir);
            }

            // Generar QR
            $qr = QrCode::format('svg')->size(300)->generate($token);
            $filePath = 'qrs/bar_' . $user->name . '.svg';

            // Guardar QR
            $result = Storage::disk('public')->put($filePath, $qr);

            if ($result) {
                $user->qr_path = $filePath;
                $user->save();
                error_log("QR saved successfully: " . $filePath);
            } else {
                error_log("Failed to save QR: " . $filePath);
            }

        } catch (Exception $e) {
            error_log("QR generation error: " . $e->getMessage());
            // Continuar sin QR si falla
        }

        return redirect()->route('login')->with('success', 'Account created successfully! Wait for the administrator to activate your account to access the dashboard.');
    }
}