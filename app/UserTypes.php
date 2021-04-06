<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTypes extends Model
{
    use HasFactory;

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


    public function users()
    {
        return $this->hasMany(User::class, 'user_type_id');
    }
}
