<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name',
        'read',
        'insert',
        'update',
        'delete',
        'admin',
        'active'
    ];

    public static $validator = [
        'name' => 'required|string|max:255',
        'read' => 'required|boolean',
        'insert' => 'required|boolean',
        'update' => 'required|boolean',
        'delete' => 'required|boolean',
        'admin' => 'required|boolean',
        'active' => 'required|boolean',
    ];

    protected $table = 'user_types';
    protected $primaryKey = 'id';
    protected $pk = 'id';

    public function users()
    {
        return $this->hasMany(User::class, 'user_type_id');
    }

}
