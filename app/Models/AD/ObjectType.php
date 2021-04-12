<?php

namespace App\Models\AD;

use Illuminate\Database\Eloquent\Model;
use App\Models\AD\ObjectinContainer;
use App\Models\AD\AD_Container;

class ObjectType extends Model
{
    protected $connection = 'pgsql';
    //protected $hidden = ['ts_id'];
    public $timestamps = false;
    protected $fillable = [
        'ts_id',
        'subtype',

    ];

    public static $validator = [
        'ts_id' => 'required|string',
        'common_name' => 'required|string',
    ];
    public function containerinfo()
    {
        return $this->belongsTo(AD_Container::class, 'ts_id');
    }
    public function computersincontainer()
    {
        //return $this->hasMany(ObjectinContainer::class, 'ts_id_parent', 'ts_id');   ->where('subtype', 'OrgUnit')
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
        //return $this->hasMany(ObjectinContainer::class, 'ts_id_parent', 'ts_id');   ->where('subtype', 'OrgUnit')
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
        //return $this->hasMany(ObjectinContainer::class, 'ts_id_parent', 'ts_id');   ->where('subtype', 'OrgUnit')
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
        //return $this->hasMany(ObjectinContainer::class, 'ts_id_parent', 'ts_id');   ->where('subtype', 'OrgUnit')
        return $this->hasMany(ObjectinContainer::class, 'ts_id_parent', 'ts_id')
        ->leftJoin(
            'traffic_source',
            'traffic_source_contains.ts_id_child',
            '=',
            'traffic_source.ts_id'
        )->where("subtype", "Container");
    }
   

    protected $table = 'traffic_source';
    protected $primaryKey = 'ts_id';
    protected $pk = 'ts_id';
}
