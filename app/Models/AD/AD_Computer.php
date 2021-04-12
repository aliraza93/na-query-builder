<?php

namespace App\Models\AD;

use Illuminate\Database\Eloquent\Model;
use App\Models\AD\ComputersinGroup;
use App\Models\AD\ObjectinContainer;



/*
use App\Models\Crm\Grp_user;
use App\Models\Crm\Container_user;
use App\Models\Crm\Ip_user;
use App\Models\Crm\Policy_users;
*/
class AD_Computer extends Model
{
 
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $fillable = [
        'object_guid',
        'common_name',
        'obj_dist_name',
        'when_created',
        'when_changed',
        'sam_account_name',
        'last_logon',
        'operating_system',
        'operating_system_service_pack',
        'operating_system_version',


    ];
   


    public static $validator = [
        'object_guid' => 'required|string',
        'common_name' => 'string|required|min:1',
        'obj_dist_name' => 'string|required|min:1',
        'when_created' => 'string|required',
        'when_changed' => 'string|required',
        'sam_account_name' => 'string|required',
        'last_logon' => 'string|nullable',
        'operating_system' => 'string|nullable',
        'operating_system_service_pack'=> 'string|nullable',
        'operating_system_version'=> 'string|nullable',



    ];
    public function memberof()
    {
        return $this->hasMany(ComputersinGroup::class, 'object_guid_child', 'object_guid');
    }
     public function computercontainers()
    {
        return $this->hasMany(ObjectinContainer::class, 'ts_id_child', 'ts_id')->leftJoin(
            'traffic_source',
            'traffic_source_contains.ts_id_parent',
            '=',
            'traffic_source.ts_id'
        )->where("subtype", "Container");
    }
    public function observed_ip()
    {
        return $this->hasMany(AD_observed_ip::class, 'object_guid', 'object_guid');
    }
    public function computerinous()
    {
        return $this->hasMany(ObjectinContainer::class, 'ts_id_child', 'ts_id')->leftJoin(
            'traffic_source',
            'traffic_source_contains.ts_id_parent',
            '=',
            'traffic_source.ts_id'
        )->where("subtype", "OrgUnit");
    }

   

    protected $table = 'ad_computer';
    protected $primaryKey = 'object_guid';
    protected $pk = 'object_guid';
    protected $keyType = 'string';
}
