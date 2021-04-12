<?php

namespace App\Models\AD;

use Illuminate\Database\Eloquent\Model;
use App\Models\AD\AD_observed_ip;
use App\Models\AD\UsersinGroup;
use App\Models\AD\ObjectinContainer;
use App\Models\AD\ObjectinPolicy;


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
        'common_name' => 'string|required|min:1',
        'user_principal_name' => 'string|nullable',
       'obj_dist_name' => 'string|required|min:1',
        'sam_account_name' => 'string|required|min:1',
        'when_created' => 'string|required',
        'when_changed' => 'string|required',
        'is_enabled' => 'string|required',
        'physical_delivery_office_name' => 'string|nullable',
        'surname' => 'string|nullable',
        'given_name' => 'string|nullable',
        'telephone_number' =>'string|nullable',
        'email_addresses' =>'string|nullable',
        'department' =>'string|nullable',
        'last_logon' => 'string|nullable',
        'logon_count' => 'string|nullable',
  
    ];
    //protected $appends = ['fullname'];

    public function userinous()
    {
        return $this->hasMany(ObjectinContainer::class, 'ts_id_child', 'ts_id')->leftJoin(
            'traffic_source',
            'traffic_source_contains.ts_id_parent',
            '=',
            'traffic_source.ts_id'
        )->where("subtype", "OrgUnit");
    }
    public function userincontainers()
    {
        return $this->hasMany(ObjectinContainer::class, 'ts_id_child', 'ts_id')->leftJoin(
            'traffic_source',
            'traffic_source_contains.ts_id_parent',
            '=',
            'traffic_source.ts_id'
        )->where("subtype", "Container");
        
    }
    public function policyuser()
    {
        return $this->hasMany(ObjectinPolicy::class, 'ts_id', 'ts_id');
    } 
    public function memberof()
    {
        return $this->hasMany(UsersinGroup::class,'object_guid_child', 'object_guid');
    }

     public function observed_ip()
    {
        return $this->hasMany(AD_observed_ip::class,'object_guid', 'object_guid');
    }
    public function userincontainersinpolicy()
    {
        //return $this->hasManyThrough(ObjectinContainer::class, 'ts_id', 'ts_id');
           return   $this->hasManyThrough(ObjectinPolicy::class,ObjectinContainer::class, 'ts_id_child', 'ts_id','ts_id')
          ->leftJoin(
            'traffic_source',
            'traffic_source_contains.ts_id_parent',
            '=',
            'traffic_source.ts_id'
        )->select('subtype', 'policy_id', 'ts_id_parent', 'ts_id_child')
        ->where('subtype','=', 'Container')
        ->leftJoin(
            'container',
            'traffic_source_contains.ts_id_parent',
            '=',
            'container.ts_id'
        )
        ->leftJoin(
            'policy',
            'traffic_source_policy.policy_id',
            '=',
            'policy.policy_id'
        )->select('policy.policy_name', 'container.common_name', 'traffic_source.subtype');
            
    }
    public function useringroupsinpolicy()
    {
        //return $this->hasManyThrough(ObjectinContainer::class, 'ts_id', 'ts_id');
        return   $this->hasManyThrough(ObjectinPolicy::class, ObjectinContainer::class, 'ts_id_child', 'ts_id', 'ts_id')
        ->leftJoin(
            'traffic_source',
            'traffic_source_contains.ts_id_parent',
            '=',
            'traffic_source.ts_id'
        )->select('subtype', 'policy_id', 'ts_id_parent', 'ts_id_child')
        ->where('subtype', '=', 'Group')
        ->leftJoin(
            'ad_group',
            'traffic_source_contains.ts_id_parent',
            '=',
            'ad_group.ts_id'
        )
            ->leftJoin(
                'policy',
                'traffic_source_policy.policy_id',
                '=',
                'policy.policy_id'
            )->select('policy.policy_name', 'ad_group.common_name', 'traffic_source.subtype');
    }
    public function getallpolicy()
    {
    return $this->userincontainersinpolicy()->union($this->useringroupsinpolicy()->first());

        
    }
    

   

    protected $table = 'ad_user';
    protected $primaryKey = 'object_guid';
    protected $pk = 'object_guid';
    protected $keyType = 'string';
    

}
