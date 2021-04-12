<?php

namespace App\Models\AD;

use Illuminate\Database\Eloquent\Model;
use App\Models\AD\AD_Computer;
use App\Models\AD\AD_Users;


class AD_observed_ip extends Model
{
    
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $fillable = [
        'ip_address',
        'timestamp_last_determined',
        'object_guid',
        'is_computer_event',
        'domain_controller',
        'user_id',
        'user_name',
        'user_domain',
              
    ];

    public static $validator = [
        'ip_address' => 'required|string',
        'timestamp_last_determined' => 'required|string',
        'object_guid' => 'required|string',
        'is_computer_event' =>'required|string',
       
       


    ];

      public function computerinfo()
    {
        return $this->belongsTo(AD_Computer::class,'object_guid', 'object_guid');
    }
    public function userinfo()
    {
        return $this->belongsTo(AD_Users::class, 'object_guid', 'object_guid');
    }

    public function getAddressAttribute()
    {
        $UserId = $this->UserId;
        $UserName = $this->UserName;
        $UserDomain = $this->UserDomain;
        return preg_replace('/\s+/', ' ', $UserId . " " . $UserName . ($UserDomain != null ? " lok. " . $UserDomain : ""));
    }

    protected $table = 'ad_observed_ip';
    protected $primaryKey = 'ip_address';
    protected $pk = 'ip_address';
    protected $keyType = 'string';
}
