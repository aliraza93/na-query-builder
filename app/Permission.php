<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'code', 'active',
    ];
    
    public static $validator = [
        'name' => 'required|string|unique:permissions,name',
        'code' => 'required|string|max:10|unique:permissions,code',
        'active' => 'boolean'
    ];
    
    public function permissionUsers()
    {
        return $this->hasMany(UserPermission::class);
    }
}
