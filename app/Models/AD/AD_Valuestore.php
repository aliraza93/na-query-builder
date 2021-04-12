<?php

namespace App\Models\AD;

use Illuminate\Database\Eloquent\Model;
/*
use App\Models\Crm\Grp_user;
use App\Models\Crm\Container_user;
use App\Models\Crm\Ip_user;
use App\Models\Crm\Policy_users;
*/
class AD_Valuestore extends Model
{
  //object_guid, common_name, obj_dist_name
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $fillable = [
        'highest_committed_usn',
        'schemaless',
       

    ];

    public static $validator = [
        'highest_committed_usn' => 'required|string',
        
       


    ];
    

   

    protected $table = 'value_store';
    protected $primaryKey = 'highest_committed_usn';
}
