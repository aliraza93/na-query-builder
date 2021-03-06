<?php

namespace App\Models\AD;

use Illuminate\Database\Eloquent\Model;
use App\Models\AD\Rules;



class Policy_rules extends Model
{
    protected $connection = 'pgsql';
    public $timestamps = true;
    const CREATED_AT = 'when_created';
    const UPDATED_AT = 'when_changed';
    protected $fillable = ['policy_id', 'priority','rule_id'];
   
    public static $validator = [
        'policy_id' => 'required',
        'rule_id' =>'required',
        'priority' => 'required',
       
    ];
    public function rulename()
    {
        return $this->belongsTo(Rules::class, 'rule_id', 'rule_id')->select('rule_id', 'rule_name');
    }


   
    protected $table = 'policy_rule';
    protected $primaryKey = 'rule_id';
    protected $pk = 'rule_id';
}
