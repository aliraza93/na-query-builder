<?php

namespace App\Models\AD;

use Illuminate\Database\Eloquent\Model;
use App\Models\AD\AD_Computer;
use App\Models\AD\AD_Groups;

/*
use App\Models\Crm\Grp_user;
use App\Models\Crm\Container_user;
use App\Models\Crm\Ip_user;
use App\Models\Crm\Policy_users;
*/
//object_guid_parent, object_guid_child
class ComputersinGroup extends Model
{
  //object_guid, common_name, obj_dist_name
    protected $connection = 'pgsql3';
    public $timestamps = false;
    protected $fillable = [
        'object_guid_parent',
        'object_guid_child',
       

    ];

    public static $validator = [
        'object_guid_parent' => 'required|string',
        'object_guid_child' => 'required|string',
        
       


    ];
    public function grpdetail()
    {
        return $this->belongsTo(AD_Groups::class, 'object_guid_parent');
    }
    public function computerdetail()
    {
        return $this->belongsTo(AD_Computer::class, 'object_guid_child');
    }
    public function memberof()
    {
        return $this->hasMany(ComputersinGroup::class, 'object_guid_child', 'object_guid_child');
    }
   

    protected $table = 'computer_in_group';
    protected $primaryKey = 'object_guid_parent';
    protected $pk = 'object_guid_parent';
    protected $keyType = 'string';
}
