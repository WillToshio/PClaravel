<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];

    // Relação com usuários
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Relação com permissões
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}
