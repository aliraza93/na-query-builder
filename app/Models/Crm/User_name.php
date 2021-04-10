<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;

use App\Models\Crm\Grp_user;
use App\Models\Crm\Container_user;
use App\Models\Crm\Ip_user;
use App\Models\Crm\Policy_users;

class User_name extends Model
{
    protected $fillable = [
        'u_name',
        'domin',
        'descr',
        'active',

    ];
    protected $appends = ['fullname'];

    public function getFullnameAttribute()
    {
        return $this->domin . " " . $this->u_name;
    }
    public function groupsname()
    {
        return $this->hasMany(Grp_user::class, 'user_id');
    }
    public function containername()
    {
        return $this->hasMany(Container_user::class, 'user_id');
    }
    public function ipsofuser()
    {
        return $this->hasMany(Ip_user::class, 'user_id');
    }
    public function policyuser()
    {
        return $this->hasMany(Policy_users::class, 'user_id');
    }

    public static $validator = [
        'u_name' => 'required|string',
        'domin' => 'required|string',
        'descr' => 'string|string',
        'active' => 'boolean',

    ];
    protected $table = 'user_name';
    protected $primaryKey = 'user_id';
    protected $pk = 'user_id';
}
