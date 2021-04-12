<?php

namespace App\Models\AD;

use Illuminate\Database\Eloquent\Model;
use App\Models\AD\Policy_rules;
use App\Models\AD\ObjectinPolicy;
use App\Models\AD\NamedPage;

class Policies extends Model
{
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $fillable = [
        'policy_name',
        'priority',
        'block_page_id'

    ];
    
    public static $validator = [
        'policy_name' => 'required|string',
        'priority' => 'required',
        'block_page_id' => 'required',


    ];
    public function blockpage(){
        return $this->belongsTo(NamedPage::class, 'block_page_id', 'block_page_id')
        ->select('block_page_id', 'title');
    }

    public function policyrule()
    {
        return $this->hasMany(Policy_rules::class,'policy_id', 'policy_id')->orderBy('priority', 'asc');
    }

//rulename
    public function policysubnet()
    {
        return $this->hasMany(ObjectinPolicy::class, 'policy_id', 'policy_id')
        ->leftJoin(
            'traffic_source',
            'traffic_source_policy.ts_id',
            '=',
            'traffic_source.ts_id'
        )->where("subtype", "IP");
    }
 public function policycomputer()
    {
        return $this->hasMany(ObjectinPolicy::class, 'policy_id', 'policy_id')
            ->leftJoin(
                'traffic_source',
                'traffic_source_policy.ts_id',
                '=',
                'traffic_source.ts_id'
            )->where("subtype", "Computer");
    }
    public function policyuser()
    {
        return $this->hasMany(ObjectinPolicy::class, 'policy_id', 'policy_id')
            ->leftJoin(
                'traffic_source',
                'traffic_source_policy.ts_id',
                '=',
                'traffic_source.ts_id'
            )->where("subtype", "User");
    }
    public function policygrp()
    {
        return $this->hasMany(ObjectinPolicy::class, 'policy_id', 'policy_id')
            ->leftJoin(
                'traffic_source',
                'traffic_source_policy.ts_id',
                '=',
                'traffic_source.ts_id'
            )->where("subtype", "Group");
    }
    public function policyous()
    {
        return $this->hasMany(ObjectinPolicy::class, 'policy_id', 'policy_id')
        ->leftJoin(
            'traffic_source',
            'traffic_source_policy.ts_id',
            '=',
            'traffic_source.ts_id'
        )->where("subtype", "OrgUnit");
    }
    public function policycontainer()
    {
        return $this->hasMany(ObjectinPolicy::class, 'policy_id', 'policy_id')
            ->leftJoin(
                'traffic_source',
                'traffic_source_policy.ts_id',
                '=',
                'traffic_source.ts_id'
            )->where("subtype", "Container");
    }
   
    
    protected $table = 'policy';
    protected $primaryKey = 'policy_id';
    protected $pk = 'policy_id';
}
