<?php

namespace App\Models\AD;

use Illuminate\Database\Eloquent\Model;

class AD_Ous_ts extends Model
{
  //ts_id, common_name, obj_dist_name
    protected $connection = 'pgsql3';
    //protected $hidden = ['ts_id'];
    public $timestamps = false;
    protected $fillable = [
        'ts_id',
        'common_name',
        'obj_dist_name',
        'when_created', 
        'when_changed'
       

    ];

    public static $validator = [
     

        'ts_id' => 'required|string',
        'common_name' => 'string|required|min:1',
        'obj_dist_name' => 'string|required|min:1',
        'when_created' => 'string|required',
        'when_changed' => 'string|required',
       


    ];
    public function computersincontainer()
    {
        return $this->hasMany(ObjectinOus::class, 'ts_id_parent', 'ts_id')
        ->leftJoin(
            'traffic_source',
            'traffic_source_contains.ts_id_child',
            '=',
            'traffic_source.ts_id'
        )->where("subtype", "Computer");
    }
    public function usersincontainer()
    {
        return $this->hasMany(ObjectinOus::class, 'ts_id_parent', 'ts_id')
        ->leftJoin(
            'traffic_source',
            'traffic_source_contains.ts_id_child',
            '=',
            'traffic_source.ts_id'
        )->where("subtype", "User");
    }
    public function groupsincontainer()
    {
        return $this->hasMany(ObjectinOus::class, 'ts_id_parent', 'ts_id')
        ->leftJoin(
            'traffic_source',
            'traffic_source_contains.ts_id_child',
            '=',
            'traffic_source.ts_id'
        )->where("subtype", "Group");
    }
    public function containerincontainer()
    {
        return $this->hasMany(ObjectinOus::class, 'ts_id_parent', 'ts_id')
        ->leftJoin(
            'traffic_source',
            'traffic_source_contains.ts_id_child',
            '=',
            'traffic_source.ts_id'
        )->where("subtype", "Container");
    }
    public function ousinous()
    {
        return $this->hasMany(ObjectinOus::class, 'ts_id_parent', 'ts_id')
        ->leftJoin(
            'traffic_source',
            'traffic_source_contains.ts_id_child',
            '=',
            'traffic_source.ts_id'
        )->where("subtype", "OrgUnit");
    }

    protected $table = 'ad_org_unit';
    protected $primaryKey = 'ts_id';
    protected $pk = 'ts_id';
    protected $keyType = 'string';
}
