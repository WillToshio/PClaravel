<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'price',
        'duration_days',
        'features',
    ];

    // Usuários com este plano ativo (plan_id)
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Histórico de usuários (pivot UserPlan)
    public function userPlans()
    {
        return $this->hasMany(UserPlan::class);
    }
}
