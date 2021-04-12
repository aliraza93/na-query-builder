<?php

namespace App\Models\AD;

use App\Models\AD\AD_Groups;

use Illuminate\Database\Eloquent\Model;


class GroupinGroup extends Model
{
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $fillable = [
        'object_guid_parent',
        'object_guid_child',  

    ];

    public static $validator = [
        'object_guid_parent' => 'required|string',
        'object_guid_child' => 'required|string',
    ];
  
    public function parentdetail()
    {
        return $this->belongsTo(AD_Groups::class, 'object_guid_parent');
    }
    public function childdetail()
    {
        return $this->belongsTo(AD_Groups::class, 'object_guid_child');
    }
    public function members()
    {
        
        return $this->hasMany(GroupinGroup::class, 'object_guid_parent', 'object_guid_parent');
    }

   

    protected $table = 'group_in_group';
    protected $primaryKey = 'object_guid_parent';
    protected $pk = 'object_guid_parent';
    protected $keyType = 'string';

}
