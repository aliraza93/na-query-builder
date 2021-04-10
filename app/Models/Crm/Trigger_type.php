<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;



class Trigger_type extends Model
{
    protected $connection = 'pgsql3';

    protected $fillable = [
        'trigger_label',
        
    ];
    protected $appends = ['text','value'];
    

    public function getTextAttribute()
    {
        return $this->trigger_label ;
    }
    public function getValueAttribute()
    {
        return $this->trigger_type;
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
        'trigger_label' => 'required|string',
       

    ];
    protected $table = 'trigger_type';
    protected $primaryKey = 'trigger_code';
    protected $pk = 'trigger_code';
    protected $keyType = 'string';

}    

