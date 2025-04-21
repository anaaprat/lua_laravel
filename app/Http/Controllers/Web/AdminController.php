<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Movement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{

    //BARS -------------------------------------------------
    public function bars()
    {
        $bars = User::where('role', 'bar')->get();
        return response()->json($bars);
    }

    public function storeBar(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $bar = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'bar',
            'credit' => 0,
        ]);

        return response()->json(['message' => 'Bar creado', 'bar' => $bar], 201);
    }

    public function showBar($id)
    {
        $bar = User::where('role', 'bar')->findOrFail($id);
        return response()->json($bar);
    }

    public function updateBar(Request $request, $id)
    {
        $bar = User::where('role', 'bar')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string',
            'email' => ['sometimes', 'email', Rule::unique('users')->ignore($bar->id)],
            'password' => 'nullable|min:6',
        ]);

        $bar->update([
            'name' => $validated['name'] ?? $bar->name,
            'email' => $validated['email'] ?? $bar->email,
            'password' => isset($validated['password']) ? Hash::make($validated['password']) : $bar->password,
        ]);

        return response()->json(['message' => 'Bar actualizado', 'bar' => $bar]);
    }

    public function deleteBar($id)
    {
        $bar = User::where('role', 'bar')->findOrFail($id);
        $bar->delete();

        return response()->json(['message' => 'Bar eliminado']);
    }

    //USERS -------------------------------------------------
    public function users()
    {
        $users = User::where('role', 'user')->get();
        return response()->json($users);
    }

    // Listar usuarios normales
    public function listUsers()
    {
        $users = User::where('role', 'user')->get();
        return response()->json($users);
    }

    // Ver detalle de un usuario
    public function showUser($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);
        return response()->json($user);
    }

    // Crear usuario normal
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'credit' => 0,
        ]);

        return response()->json(['message' => 'Usuario creado', 'user' => $user], 201);
    }

    // Editar usuario normal
    public function updateUser(Request $request, $id)
    {
        $user = User::where('role', 'user')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string',
            'email' => ['sometimes', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|min:6',
        ]);

        $user->update([
            'name' => $validated['name'] ?? $user->name,
            'email' => $validated['email'] ?? $user->email,
            'password' => isset($validated['password']) ? Hash::make($validated['password']) : $user->password,
        ]);

        return response()->json(['message' => 'Usuario actualizado', 'user' => $user]);
    }

    // Eliminar usuario normal
    public function deleteUser($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Usuario eliminado']);
    }


    //MOVEMENTS ---------------------------------------------
    public function movements()
    {
        $movements = Movement::with(['user', 'bar'])->latest()->get();
        return response()->json($movements);
    }



}
