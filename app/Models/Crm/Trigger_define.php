<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;



class Trigger_define extends Model
{
    protected $fillable = [
        'tr_name',
        'tr_type',
        
    ];
    public $timestamps = false;
    protected $appends = ['fullname'];

    public function getFullnameAttribute()
    {
        return $this->domin . " " . $this->u_name;
    }
    /*
    public function groupsname()
    {
        return $this->hasMany(Grp_user::class, 'user_id');
    }
    public function containername()
    {
        return $this->hasMany(Container_user::class, 'user_id');
    }
    public function ipsofuser()
    {
        return $this->hasMany(Ip_user::class, 'user_id');
    }*/

    public static $validator = [
        'tr_name' => 'required|string',
        'tr_type' => 'required|string',

    ];
    protected $table = 'trigger_define';
    protected $primaryKey = 'tr_id';
    protected $pk = 'tr_id';
}
