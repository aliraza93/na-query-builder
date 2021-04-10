<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;
use App\Models\Crm\Policy_container;
use App\Models\Crm\Users_container;
use App\Models\Crm\Sub_container;
use App\Models\Crm\Container_subnet;
use App\Models\Crm\Grp_conainer;
class Container extends Model
{
    protected $fillable = [
        'container_name',
        'container_priority',
        'descr',
        'active',

    ];
    protected $appends = ['fullname'];
    public $timestamps = false;
    public function getFullnameAttribute()
    {
        return $this->container_priority . " " . $this->container_name;
    }
   
    public function usercontainer()
    {
        return $this->hasMany(Users_container::class, 'container_id');
    }

    public function subcontainer()
    {
        return $this->hasMany(Sub_container::class, 'container_id');
    }
    public function ipsname()
    {
        return $this->hasMany(Container_subnet::class, 'container_id');
    }
    public function containergrp()
    {
        return $this->hasMany(Grp_conainer::class, 'container_id');
    }
    public function policycontainer()
    {
        return $this->hasMany(Policy_container::class, 'container_id');
    }
    public static $validator = [
        'container_name' => 'required|string',
        'container_priority' => 'required|string',
        'descr' => 'string|string',
        'container_lvl' => 'string|string',

    ];
    protected $table = 'container';
    protected $primaryKey = 'container_id';
    protected $pk = 'container_id';
}
