<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;
use App\Models\Crm\Container;

use App\Models\Crm\Policies;

class Policy_container extends Model
{
    protected $fillable = ['policy_id', 'container_id', 'priority'];

    public static $validator = [
        'policy_id' => 'required|string',
        'container_id' => 'required|string',
        'priority' => 'required|string',
       
    ];

    public $timestamps = false;
    public function contname()
    {
        return $this->belongsTo(Container::class, 'container_id');
    }
    public function policyname()
    {
        return $this->belongsTo(Policies::class, 'policy_id');
    }


   
    protected $table = 'policy_container';
    protected $primaryKey = 'rec_id';
    protected $pk = 'rec_id';
}
