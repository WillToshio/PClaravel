<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Plan;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ForgotPasswordRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Registro de usuário
     */
    public function register(RegisterRequest $request)
    {

        $default_role = Role::nameInsensitive('user')->first();
        $default_plan = Plan::nameInsensitive('free')->first();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $default_role, // fixo
            'plan_id' => $default_plan  // fixo
        ]);

        return response()->json(['message' => 'Usuário registrado com sucesso', 'user' => $user], 201);
    }

    /**
     * Login via Sanctum
     */
    public function login(LoginRequest $request)
    {

        $user = User::where('email', $request['email'])->first();

        if (!$user || !Hash::check($request['password'], $user->password)) {
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
        try {
            $user = $request->user();

            // Verifica se o usuário está autenticado
            if (!$user) {
                return response()->json([
                    'message' => 'Usuário não autenticado'
                ], 401);
            }

            // Carrega todas as relações necessárias
            $user->load([
                'roles',
                'plans',
                'planHistory.plan'
            ]);

            // Encontra o plano ativo atual
            $activePlan = $user->plans
                ->where('subscription.is_active', true)
                ->where('subscription.expires_at', '>', now())
                ->first();

            // Constrói o perfil completo
            $profile = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'email_verified_at' => $user->email_verified_at,
                'is_active' => $user->is_active,
                'last_login' => $user->last_login,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'roles' => $user->roles->pluck('name')->toArray(),
                'current_plan' => $activePlan ? [
                    'name' => $activePlan->name,
                    'price' => $activePlan->price,
                    'duration_days' => $activePlan->duration_days,
                    'features' => $activePlan->features,
                    'starts_at' => $activePlan->subscription->starts_at ?? '',
                    'expires_at' => $activePlan->subscription->expires_at ?? '',
                    'updated_at' => $activePlan->subscription->updated_at ?? '',
                    'is_active' => $activePlan->subscription->is_active,
                ] : null,
                'plan_history' => $user->planHistory->map(function($userPlan) {
                    return [
                        'plan_name' => $userPlan->plan->name ?? 'Plano não encontrado',
                        'starts_at' => $userPlan->starts_at?->toDateTimeString(),
                        'expires_at' => $userPlan->expires_at?->toDateTimeString(),
                        'created_at' => $userPlan->created_at?->toDateTimeString(),
                        'updated_at' => $userPlan->updated_at?->toDateTimeString(),
                        'is_active' => $userPlan->is_active,
                    ];
                })->toArray(),
            ];

            return response()->json([
                'message' => 'Perfil recuperado com sucesso',
                'profile' => $profile
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao recuperar perfil',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset de senha (opcional)
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        // Pode implementar envio de token por email usando Notification
    }
}
