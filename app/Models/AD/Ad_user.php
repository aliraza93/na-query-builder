<?php

namespace App\Models\AD;

use Illuminate\Database\Eloquent\Model;


class Ad_user extends Model
{

    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $fillable = [
        'object_guid',
        'common_name',
        'surname',
        'given_name',
        'sam_account_name',
        'physical_delivery_office_name',
        'telephone_number',
        'email_addresses',
        'department',
        'obj_dist_name',
        'last_logon',
        'logon_count',
        'user_principal_name',
        'is_enabled',
        'when_created',
        'when_changed',
        
        
    ];

    public static $validator = [
        'object_guid' => 'string',
        'common_name' => 'string',
        'surname' => 'string',
        'given_name' => 'string',
        'sam_account_name' => 'string',
        'physical_delivery_office_name' => 'string',
        'telephone_number' => 'string',
        'email_addresses' => 'string',
        'department' => 'string',
        'obj_dist_name' => 'string',
        'last_logon' => 'string',
        'logon_count' => 'string',
        'user_principal_name' => 'string',
        'is_enabled' => 'string',
        'when_created' => 'string',
        'when_changed' => 'string',
        

    ];
    //SELECT "object_guid", common_name, surname, given_name, sam_account_name, sam_account_name, physical_delivery_office_name, telephone_number, email_addresses, department, obj_dist_name, last_logon


    public function getAddressAttribute()
    {
        $object_guid = $this->object_guid;
        $common_name = $this->common_name;
        $obj_dist_name = $this->obj_dist_name;
        return preg_replace('/\s+/', ' ', $object_guid . " " . $common_name . ($obj_dist_name != null ? " lok. " . $obj_dist_name : ""));
    }

    protected $table = 'ad_user';
    protected $primaryKey = 'object_guid';
    protected $pk = 'object_guid';
    protected $keyType = 'string';
}
