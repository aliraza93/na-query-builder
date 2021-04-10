<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;

use App\Models\Crm\Subnet_container;
use App\Models\Crm\Policy_subnet;



class Sub_net extends Model
{
    protected $fillable = [
        'ip_addr',
        'descr'

    ];
    protected $appends = ['fullname'];
    public $timestamps = false;
    public function getFullnameAttribute()
    {
        return $this->ip_addr;
    }

    public static $validator = [
        'ip_addr' => 'required|string',
        'descr' => 'string|string',


    ];

    public function usergrp()
    {
        return $this->hasMany(Users_grp::class, 'ip_id');
    }
    public function subnetcontainre()
    {
        return $this->hasMany(Subnet_container::class, 'ip_id');
    }
    public function policysubnet()
    {
        return $this->hasMany(Policy_subnet::class, 'ip_id');
    }
    protected $table = 'ips_subnet';
    protected $primaryKey = 'ip_id';
    protected $pk = 'ip_id';
}
