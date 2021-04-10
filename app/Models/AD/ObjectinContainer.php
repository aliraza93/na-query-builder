<?php

namespace App\Models\AD;
use Illuminate\Database\Eloquent\Model;
use App\Models\AD\AD_Groups;
use App\Models\AD\AD_Container;
use App\Models\AD\AD_Computer;
use App\Models\AD\AD_Users;
use App\Models\AD\ObjectinPolicy;
use App\Models\AD\IP_address;
use App\Models\AD\AD_Ous_ts;


class ObjectinContainer extends Model
{
    protected $connection = 'pgsql3';
   
    protected $fillable = ['ts_id_parent', 'ts_id_child'];
    public static $validator = [
        'ts_id_parent' => 'required|string',
        'ts_id_child' => 'required|string',
    ];
    public $timestamps = false;
    public function ousdetail()
    {
        return $this->belongsTo(AD_Ous_ts::class, 'ts_id_child', 'ts_id');
    }
    public function parentous()
    {
        return $this->belongsTo(AD_Ous_ts::class, 'ts_id_parent', 'ts_id');
    }
    public function subnetdetail()
    {
        return $this->belongsTo(IP_address::class, 'ts_id_child', 'ts_id');
    }
    public function containerdetail()
    {
        return $this->belongsTo(AD_Container::class, 'ts_id_child', 'ts_id');
    }
    public function grpdetail()
    {
        return $this->belongsTo(AD_Groups::class, 'ts_id_child', 'ts_id');
    }
    public function userdetail()
    {
        //test
        return $this->belongsTo(Ad_users::class, 'ts_id_child', 'ts_id');
    }
    public function computerdetail()
    {
        return $this->belongsTo(AD_Computer::class,'ts_id_child', 'ts_id');
    }
    public function parentcontainer()
    {
        return $this->belongsTo(AD_Container::class, 'ts_id_parent', 'ts_id');
    }
    public function userincontainersinpolicy()
    {
        return $this->hasMany(ObjectinPolicy::class, 'ts_id', 'ts_id');
    }
   
//User Group Computer  Container
    protected $table = 'traffic_source_contains';
    protected $primaryKey = 'ts_id_parent';
    protected $pk = 'ts_id_parent';
}
