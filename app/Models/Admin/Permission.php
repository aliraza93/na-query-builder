<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\UserPermission;

class Permission extends Model
{
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

    protected $table = 'permissions';
    protected $primaryKey = 'id';
    protected $pk = 'id';
}
