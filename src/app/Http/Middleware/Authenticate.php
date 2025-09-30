<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * O que retornar quando o usuário não estiver autenticado.
     */
    protected function redirectTo($request): ?string
    {
        if (!$request->expectsJson()) {
            abort(response()->json([
                'message' => 'Não autenticado'
            ], 401));
        }

        return null;
    }
}
