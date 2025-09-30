<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'is_active',
        'last_login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relacionamento muitos-para-muitos com roles (papeis)
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    // Relacionamento muitos-para-muitos com plans (planos)
    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'user_plans')
                    ->withPivot('starts_at', 'expires_at', 'is_active')
                    ->withTimestamps()
                    ->as('subscription');
    }

    /**
     * Relação um-para-muitos com user_plans (histórico completo)
     */
    public function planHistory()
    {
        return $this->hasMany(UserPlan::class);
    }

    // Relacionamento um-para-muitos com permissions (se for específico do usuário)
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}
