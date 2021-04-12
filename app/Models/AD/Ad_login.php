<?php

namespace App\Models\AD;

use Illuminate\Database\Eloquent\Model;


/*
SELECT "Timestamp", "IsComputerEvent", "IPAddress", "UserId", "UserName", "UserDomain", "DomainController"
	FROM public.ad_login;
*/

class Ad_login extends Model
{
    
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $fillable = [
        'ip_address',
        'timestamp_last_determined',
        'object_guid_user',
        'object_guid_computer',
              
        
    ];

    public static $validator = [
        'ip_address' => 'required|string',
        'timestamp_last_determined' => 'required|string',
        'object_guid_user' => 'required|string',
        'object_guid_computer' => 'string',
       
       


    ];
    //SELECT "UserId", UserName, surname, given_name, sam_account_name, sam_account_name, physical_delivery_office_name, phone_number, email, department, UserDomain, last_logon


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
}
