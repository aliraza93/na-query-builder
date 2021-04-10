<?php

namespace App;


use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Admin\UserPermission;
use App\Admin\UserType;

class User extends Authenticatable 
{
    use Notifiable;
    protected $fillable = [
        'name',
        'email',
        'password',
        'initial_password',
        'user_type_id',
        'active'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
    public static $validator = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:login_user,email',
        'user_type_id' => 'exists:user_types,id',
        'active' => 'nullable',
    ];

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function userPermissions()
    {
        return $this->hasMany(UserPermission::class);
    }

    public function userType()
    {
        return $this->belongsTo(UserType::class, 'user_type_id');
    }
    
    protected $table = 'login_user';
    protected $primaryKey = 'id';
    protected $pk = 'id';
}