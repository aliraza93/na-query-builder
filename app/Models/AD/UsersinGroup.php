<?php

namespace App\Models\AD;

use Illuminate\Database\Eloquent\Model;
use App\Models\AD\Ad_user;
use App\Models\AD\AD_Groups;

class UsersinGroup extends Model
{
    protected $connection = 'pgsql3';
    protected $fillable = ['object_guid_parent', 'object_guid_child'];
    public static $validator = [
        'object_guid_parent' => 'required|string',
        'object_guid_child' => 'required|string',
    ];
    public $timestamps = false;
    public function grpdetail()
    {
        return $this->belongsTo(AD_Groups::class, 'object_guid_parent');
    }
    public function userdetail()
    {
        return $this->belongsTo(Ad_user::class, 'object_guid_child');
    }
    public function memberof()
    {
        return $this->hasMany(UsersinGroup::class, 'object_guid_child', 'object_guid_child');
    }

    protected $table = 'user_in_group';
    protected $primaryKey = 'object_guid_child';
    protected $pk = 'object_guid_child';
    protected $keyType = 'string';
}
