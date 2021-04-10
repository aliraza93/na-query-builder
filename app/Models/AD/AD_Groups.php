<?php

namespace App\Models\AD;

use Illuminate\Database\Eloquent\Model;

use App\Models\AD\GroupinGroup;
use App\Models\AD\UsersinGroup;
use App\Models\AD\ObjectinContainer;
use App\Models\AD\ComputersinGroup;



/*
use App\Models\Crm\Container_user;
use App\Models\Crm\Ip_user;
use App\Models\Crm\Policy_users;
*/
class AD_Groups extends Model
{


    protected $connection = 'pgsql3';
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
        'common_name' => 'string|required|min:1',
        'obj_dist_name' => 'string|required|min:1',
        'when_created' => 'string|required',
        'when_changed' => 'string|required',


    ];
    public function grpinous()
    {
        return $this->hasMany(ObjectinContainer::class, 'ts_id_child', 'ts_id')->leftJoin(
            'traffic_source',
            'traffic_source_contains.ts_id_parent',
            '=',
            'traffic_source.ts_id'
        )->where("subtype", "OrgUnit");
    }
    public function grpsincontainers()
    {
        return $this->hasMany(ObjectinContainer::class, 'ts_id_child', 'ts_id')->leftJoin(
            'traffic_source',
            'traffic_source_contains.ts_id_parent',
            '=',
            'traffic_source.ts_id'
        )->where("subtype", "Container");
    } 

    public function memberof()
    {
        return $this->hasMany(GroupinGroup::class, 'object_guid_child', 'object_guid');
    }
    public function members_grps()
    {
        return $this->hasMany(GroupinGroup::class, 'object_guid_parent', 'object_guid');
    }
    public function members_users()
    {
        return $this->hasMany(UsersinGroup::class, 'object_guid_parent', 'object_guid');
    }
    public function members_computers()
    {
        return $this->hasMany(ComputersinGroup::class, 'object_guid_parent', 'object_guid');
    }
    

    protected $table = 'ad_group';
    protected $primaryKey = 'object_guid';
    protected $pk = 'object_guid';
    protected $keyType = 'string';
    
}
