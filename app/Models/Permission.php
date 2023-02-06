<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['path', 'method', 'status'];


    public function role()
    {
        return $this->belongsToMany(Role::class, 'RolePermission', 'permission_id', 'role_id');
    }

}
