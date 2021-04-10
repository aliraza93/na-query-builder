<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;
use App\Models\Crm\Policy_rules;
use App\Models\Crm\Policy_users;
use App\Models\Crm\Policy_grp;
use App\Models\Crm\Policy_container;
use App\Models\Crm\Policy_subnet;


class Policies extends Model
{
    protected $fillable = [
        'policy_name',
        'priority',
        'descr'

    ];
    protected $appends = ['fullname'];

    public function getFullnameAttribute()
    {
        return $this->domin . " " . $this->u_name;
    }
    public $timestamps = false;
    public static $validator = [
        'policy_name' => 'required|string',
        'priority' => 'required|string',
        'descr' => 'string|string',


    ];

    public function policyrule()
    {
        return $this->hasMany(Policy_rules::class, 'policy_id');
    }
    public function policyuser()
    {
        return $this->hasMany(Policy_users::class, 'policy_id');
    }
    public function policygrp()
    {
        return $this->hasMany(Policy_grp::class, 'policy_id');
    }
    public function policycontainer()
    {
        return $this->hasMany(Policy_container::class, 'policy_id');
    }
    public function policysubnet()
    {
        return $this->hasMany(Policy_subnet::class, 'policy_id');
    }

    
    protected $table = 'policy';
    protected $primaryKey = 'policy_id';
    protected $pk = 'policy_id';
}
