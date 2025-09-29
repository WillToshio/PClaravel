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

     // Relacionamento muitos-para-muitos com users
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }

    // Relação com permissões
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function scopeNameInsensitive($query, $name)
    {
        return $query->whereRaw('UPPER(name) = ?', [strtoupper($name)]);
    }
}
