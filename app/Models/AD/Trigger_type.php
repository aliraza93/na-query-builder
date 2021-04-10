<?php

namespace App\Models\AD;

use Illuminate\Database\Eloquent\Model;



class trigger_type extends Model
{
    protected $connection = 'pgsql3';

    protected $fillable = [
        'trigger_label',
        
    ];
   
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
    protected $table = 'rule_builder_trigger';
    protected $primaryKey = 'trigger_code';
    protected $pk = 'trigger_code';
    protected $keyType = 'string';

}    

