<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;
use App\Models\Crm\Group_name;
use App\Models\Crm\Policies;
//Policies


class Policy_grp extends Model
{
    protected $fillable = ['policy_id', 'group_id', 'priority'];

    public static $validator = [
        'policy_id' => 'required|string',
        'group_id' => 'required|string',
        'priority' => 'required|string',
       
    ];

    public $timestamps = false;
    public function grpname()
    {
        return $this->belongsTo(Group_name::class, 'group_id');
    }
    public function policyname()
    {
        return $this->belongsTo(Policies::class, 'policy_id');
    }

   
    protected $table = 'policy_group';
    protected $primaryKey = 'rec_id';
    protected $pk = 'rec_id';
}
