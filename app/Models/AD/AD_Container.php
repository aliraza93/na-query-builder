<?php

namespace App\Models\AD;

use Illuminate\Database\Eloquent\Model;

use App\Models\AD\UsersinGroup;

class AD_Container extends Model
{
  
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $fillable = [
        'container_uid',
        'common_name',
        ];

    public static $validator = [
        'common_name' => 'required|string',
        
    ];
  
    public function computersincontainer()
    {
        return $this->hasMany(ObjectinContainer::class, 'ts_id_parent', 'ts_id')
            ->leftJoin(
                'traffic_source',
                'traffic_source_contains.ts_id_child',
                '=',
                'traffic_source.ts_id'
            )->where("subtype", "Computer");
    }
    public function usersincontainer()
    {
        return $this->hasMany(ObjectinContainer::class, 'ts_id_parent', 'ts_id')
            ->leftJoin(
                'traffic_source',
                'traffic_source_contains.ts_id_child',
                '=',
                'traffic_source.ts_id'
            )->where("subtype", "User");
    }
    public function groupsincontainer()
    {
        return $this->hasMany(ObjectinContainer::class, 'ts_id_parent', 'ts_id')
            ->leftJoin(
                'traffic_source',
                'traffic_source_contains.ts_id_child',
                '=',
                'traffic_source.ts_id'
            )->where("subtype", "Group");
    }
    public function containerincontainer()
    {
        return $this->hasMany(ObjectinContainer::class, 'ts_id_parent', 'ts_id')
            ->leftJoin(
                'traffic_source',
                'traffic_source_contains.ts_id_child',
                '=',
                'traffic_source.ts_id'
            )->where("subtype", "Container");
    }
    public function subnetincontainer()
    {
        return $this->hasMany(ObjectinContainer::class, 'ts_id_parent', 'ts_id')
        ->leftJoin(
            'traffic_source',
            'traffic_source_contains.ts_id_child',
            '=',
            'traffic_source.ts_id'
        )->where("subtype", "IP");
    }
    public function ousincontainer()
    {
        return $this->hasMany(ObjectinContainer::class, 'ts_id_parent', 'ts_id')
            ->leftJoin(
                'traffic_source',
                'traffic_source_contains.ts_id_child',
                '=',
                'traffic_source.ts_id'
            )->where("subtype", "OrgUnit");
    }
   
   

    protected $table = 'container';
    protected $primaryKey = 'ts_id';
    protected $pk = 'ts_id';
    

}
