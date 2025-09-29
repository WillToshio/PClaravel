<?php
// app/Models/Permission.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'role_id',
        'user_id',
        'can_read',
        'can_write',
        'can_delete',
        'requires_approval'
    ];

    protected $casts = [
        'can_read' => 'boolean',
        'can_write' => 'boolean',
        'can_delete' => 'boolean',
        'requires_approval' => 'boolean',
    ];

    // Relacionamento pertence a Module
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    // Relacionamento pertence a Role (opcional)
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Relacionamento pertence a User (opcional)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
