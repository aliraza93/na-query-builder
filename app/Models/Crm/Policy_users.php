<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;
use App\Models\Crm\User_name;
use App\Models\Crm\Policies;



class Policy_users extends Model
{
    protected $fillable = ['policy_id', 'user_id', 'priority'];

    public static $validator = [
        'policy_id' => 'required|string',
        'user_id' => 'required|string',
        'priority' => 'required|string',
       
    ];

    public $timestamps = false;
    public function username()
    {
        return $this->belongsTo(User_name::class, 'user_id');
    }
    public function policyname()
    {
        return $this->belongsTo(Policies::class, 'policy_id');
    }

   
    protected $table = 'policy_users';
    protected $primaryKey = 'rec_id';
    protected $pk = 'rec_id';
}
