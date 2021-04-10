<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;
use App\Models\Crm\Users_grp;
use App\Models\Crm\Container_grp;
use App\Models\Crm\Policy_grp;
class Group_name extends Model
{
    protected $fillable = [
        'grp_name',
        'domin',
        'descr'

    ];
    protected $appends = ['fullname'];

    public function getFullnameAttribute()
    {
        return $this->domin . " " . $this->u_name;
    }

    public static $validator = [
        'grp_name' => 'required|string',
        'domin' => 'required|string',
        'descr' => 'string|string',


    ];

    public function usergrp()
    {
        return $this->hasMany(Users_grp::class, 'group_id');
    }
    public function containergrp()
    {
        return $this->hasMany(Container_grp::class, 'group_id');
    }
    public function policygrp()
    {
        return $this->hasMany(Policy_grp::class, 'group_id');
    }
    protected $table = 'group_name';
    protected $primaryKey = 'group_id';
    protected $pk = 'group_id';
}
