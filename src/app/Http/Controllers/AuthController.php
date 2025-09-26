<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Registro de usuário
     */
    public function register(Request $request)
    {

        $default_role = Role::nameInsensitive('user')->first();
        $dqfault_plan = Plan::nameInsensitive('free')->first();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role_id' => $default_role, // fixo
            'plan_id' => $dqfault_plan  // fixo
        ]);

        return response()->json(['message' => 'Usuário registrado com sucesso', 'user' => $user], 201);
    }

    /**
     * Login via Sanctum
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Credenciais inválidas.'],
            ]);
        }

        // Criar token
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'Login realizado com sucesso',
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * Logout do usuário
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

    /**
     * Retorna o perfil do usuário
     */
    public function me(Request $request)
    {
        // Usuário autenticado via Sanctum
        $user = $request->user();

        // Eager load das relações
        $user->load('role', 'planHistory.plan');

        $profile = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role->name ?? null,
            'plan' => $user->plan->name ?? null,          // plano ativo
            'plan_history' => $user->planHistory->map(function($p) {
                return [
                    'plan_name' => $p->plan->name ?? null,
                    'starts_at' => $p->starts_at?->toDateTimeString(),
                    'expires_at' => $p->expires_at?->toDateTimeString(),
                    'is_active' => $p->is_active,
                ];
            }),
        ];

        return response()->json([
            'profile' => $profile
        ]);
    }

    /**
     * Reset de senha (opcional)
     */
    public function forgotPassword(Request $request)
    {
        // Pode implementar envio de token por email usando Notification
    }
}
