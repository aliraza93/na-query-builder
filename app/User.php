<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
  use HasApiTokens, Notifiable, HasRoles;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'email',
    'password',
    'initial_password',
    'user_type_id',
    'active'
  ];


  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  public static $validator = [
    'name' => 'required|string|max:255',
    'email' => 'required|string|email|max:255|unique:login_user,email',
    'user_type_id' => 'exists:user_types,id',
    'active' => 'nullable',
  ];

  public function userPermissions()
  {
      return $this->hasMany(UserPermission::class);
  }

  public function userType()
  {
      return $this->belongsTo(UserType::class, 'user_type_id');
  }
}
