<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;



class Policy_rules extends Model
{
    protected $fillable = ['policy_id', 'r_action', 'descr', 'priority', 'r_condition'];
    protected $appends = ['fullname'];
    public function getFullnameAttribute()
    {
        if ($this->r_action == 1){
            return 'Allow';
        }else if ($this->r_action == 2){
            return 'Deny';
        } else if ($this->r_action == 3) {
            return 'Filter';
        }else{ return "err";}

       
    }
    public static $validator = [
        'policy_id' => 'required|string',
        'r_action' => 'required|string',
        'descr' => 'string|string',
        'priority' => 'required|string',
        'r_condition' => 'required|string',
    ];

    public $timestamps = false;

   
    protected $table = 'policy_rules';
    protected $primaryKey = 'rule_id';
    protected $pk = 'rule_id';
}
