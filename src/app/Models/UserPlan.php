<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'plan_id',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /** RELACIONAMENTOS **/

    // Usuário dono deste plano
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Plano associado
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
/**
 * // Registrar um novo plano
 * $user = User::find(1);
 * $user->planHistory()->create([
 *      'plan_id' => $newPlanId,
 *      'starts_at' => now(),
 *      'expires_at' => now()->addDays($duration),
 *      'is_active' => true,
 * ]);
 */

/**
 * // Atualiza plano ativo
 * $user->plan_id = $newPlanId;
 * $user->save();
 */

/**
 * // Obter histórico de planos
 * foreach ($user->planHistory as $history) {
 *     echo $history->plan->name . ' | ' . $history->starts_at . ' até ' . $history->expires_at;
 * }
 */

