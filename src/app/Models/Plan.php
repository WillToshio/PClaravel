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

    protected $casts = [
        'price' => 'decimal:2',
        'features' => 'array'
    ];

    // Usuários com este plano ativo (plan_id)
    public function users()
    {

        return $this->belongsToMany(User::class, 'user_plans')
                    ->withPivot('starts_at', 'expires_at', 'is_active', 'updated_at')
                    ->withTimestamps();
    }

    // Histórico de usuários (pivot UserPlan)
    public function userPlans()
    {

        return $this->belongsToMany(User::class, 'user_plans')
                    ->withPivot('starts_at', 'expires_at', 'is_active', 'updated_at')
                    ->withTimestamps();
    }
    public function scopeNameInsensitive($query, $name)
    {
        return $query->whereRaw('UPPER(name) = ?', [strtoupper($name)]);
    }
}
