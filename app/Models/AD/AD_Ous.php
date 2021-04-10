<?php

namespace App\Models\AD;

use Illuminate\Database\Eloquent\Model;

class AD_Ous extends Model
{
  //object_guid, common_name, obj_dist_name
    protected $connection = 'pgsql3';
    //protected $hidden = ['ts_id'];
    public $timestamps = false;
    protected $fillable = [
        'object_guid',
        'common_name',
        'obj_dist_name',
        'when_created', 
        'when_changed'
       

    ];

    public static $validator = [
     

        'object_guid' => 'required|string',
        'common_name' => 'string|required|min:1',
        'obj_dist_name' => 'string|required|min:1',
        'when_created' => 'string|required',
        'when_changed' => 'string|required',
       


    ];

    protected $table = 'ad_org_unit';
    protected $primaryKey = 'object_guid';
    protected $pk = 'object_guid';
    protected $keyType = 'string';
}
