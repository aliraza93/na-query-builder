<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;
use App\Models\Crm\Sub_net;
use App\Models\Crm\Policies;



class Policy_subnet extends Model
{
    protected $fillable = ['policy_id', 'ip_id', 'priority'];

    public static $validator = [
        'policy_id' => 'required|string',
        'ip_id' => 'required|string',
        'priority' => 'required|string',
       
    ];

    public $timestamps = false;
    public function subname()
    {
        return $this->belongsTo(Sub_net::class, 'ip_id');
    }
    public function policyname()
    {
        return $this->belongsTo(Policies::class, 'policy_id');
    }

   
    protected $table = 'policy_ips';
    protected $primaryKey = 'rec_id';
    protected $pk = 'rec_id';
}
