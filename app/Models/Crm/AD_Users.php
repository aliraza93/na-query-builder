<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;
/*
use App\Models\Crm\Grp_user;
use App\Models\Crm\Container_user;
use App\Models\Crm\Ip_user;
use App\Models\Crm\Policy_users;
*/
class AD_Users extends Model
{
  
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $fillable = [
        'object_guid',
        'common_name',
        'surname',
        'given_name',
        'sam_account_name',
        'physical_delivery_office_name',
        'telephone_number',
        'email_addresses',
        'department',
        'obj_dist_name',
        'last_logon',
        'logon_count',
        'user_principal_name',
        'is_enabled',
        'when_created',
        'when_changed',


    ];

    public static $validator = [
        'object_guid' => 'required|string',
        'common_name' => 'string',
        'surname' => 'string',
        'given_name' => 'string',
        'sam_account_name' => 'string',
        'physical_delivery_office_name' => 'string',
        'telephone_number' => 'string',
        'email_addresses' => 'string',
        'department' => 'string',
        'obj_dist_name' => 'string',
        'last_logon' => 'string',
        'logon_count' => 'string',
        'user_principal_name' => 'string',
        'is_enabled' => 'string',
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

   

    protected $table = 'ad_user';
    protected $primaryKey = 'object_guid';
    protected $pk = 'object_guid';
}
