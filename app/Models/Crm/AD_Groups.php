<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;
/*
use App\Models\Crm\Grp_user;
use App\Models\Crm\Container_user;
use App\Models\Crm\Ip_user;
use App\Models\Crm\Policy_users;
*/
class AD_Groups extends Model
{
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $fillable = [
        'object_guid',
        'common_name',
        'obj_dist_name',
        'when_created',
        'when_changed',


    ];

    public static $validator = [
        'object_guid' => 'required|string',
        'common_name' => 'string',
        'obj_dist_name' => 'string',
        'when_created' => 'string',
        'when_changed' => 'string',


    ];
    //protected $appends = ['fullname'];


    /*
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
*/

   

    protected $table = 'ad_group';
    protected $primaryKey = 'object_guid';
    protected $pk = 'object_guid';
}
