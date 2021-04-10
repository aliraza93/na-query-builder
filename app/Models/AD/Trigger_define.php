<?php

namespace App\Models\AD;

use Illuminate\Database\Eloquent\Model;



class Trigger_define extends Model
{
    protected $connection = 'pgsql3';
    protected $appends = ['text', 'value'];


    public function getTextAttribute()
    {
        return $this->choice_label;
    }
    public function getValueAttribute()
    {
        return $this->choice_code;
    }
    /*
    protected $fillable = [
        'choice_label',
        'input_code',
        
    ];
   
    public $timestamps = false;
   
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
        'choice_label' => 'required|string',
        'input_code' => 'required|string',

    ];
    protected $table = 'rule_builder_choice';
    protected $primaryKey = 'input_code';
    protected $pk = 'input_code';
    protected $keyType = 'string';
}
