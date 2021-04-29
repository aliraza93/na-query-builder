<?php

namespace App\Models\AD;
use Illuminate\Database\Eloquent\Model;
use App\Models\AD\AD_Groups;
use App\Models\AD\AD_Container;
use App\Models\AD\AD_Computer;
use App\Models\AD\AD_Users;
use App\Models\AD\IP_address;
use App\Models\AD\Policies;
use App\Models\AD\AD_Ous;

class ObjectinPolicy extends Model
{

    protected $connection = 'pgsql';
   
   
    protected $fillable = 
     [
        'policy_id',
        'ts_id' ,
        'enforced_flag' ,
        'priority_policy',
        'rules_enforced' ,
       

    ];
    public static $validator = [
        'policy_id' => 'required',
        'ts_id' => 'required',
        'enforced_flag' => 'required',
        'priority_policy' => 'required',
     //   'rules_enforced' => 'string',

    ];
    public $timestamps = false;
    public function contname()
    {
        return $this->belongsTo(AD_Container::class, 'ts_id', 'ts_id')->select('ts_id', 'common_name');
    }
    public function grpname()
    {
        return $this->belongsTo(AD_Groups::class, 'ts_id', 'ts_id')->select('ts_id', 'common_name');
    }
    public function username()
    {
        //test
        return $this->belongsTo(Ad_users::class, 'ts_id', 'ts_id')->select('ts_id', 'common_name');
    }
    public function computername()
    {
        return $this->belongsTo(AD_Computer::class,'ts_id', 'ts_id')->select('ts_id', 'common_name');
    }
    public function ousname()
    {
        //test
        return $this->belongsTo(AD_Ous::class, 'ts_id', 'ts_id')->select('ts_id', 'common_name');
    }
    public function subname()
    {
        return $this->belongsTo(IP_address::class, 'ts_id', 'ts_id')->select('ts_id', 'name');
    }
    public function policyname()
    {
        return $this->belongsTo(Policies::class, 'policy_id', 'policy_id');
    }

  
   //subname   policydetail
//User Group Computer  Container
    protected $table = 'traffic_source_policy';
    protected $primaryKey = 'policy_id';
    protected $pk = 'policy_id';
}
