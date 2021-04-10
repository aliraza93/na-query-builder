<?php

namespace App\Models\AD;

use Illuminate\Database\Eloquent\Model;

class Rules extends Model
{
 
    protected $connection = 'pgsql3';
    public $timestamps = false;
    protected $fillable = [
        'rule_id',
        'rule_name',
        'match_conditions',
        'match_action',
        'immediate_flag',
       
    ];
   


    public static $validator = [
        'rule_name' => 'string',
        'match_conditions' => 'string',
        'match_action' => 'string',
        'immediate_flag' => 'required',
       
    ];
    

   

    protected $table = 'rule';
    protected $primaryKey = 'rule_id';
    protected $pk = 'rule_id';
  
}
