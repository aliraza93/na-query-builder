<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;
use App\Models\Crm\Group_name;


class Grp_conainer extends Model
{
    protected $fillable = ['container_id', 'group_id'];

    public static $validator = [
        'container_id' => 'required|string',
        'group_id' => 'required|string',
    ];

    public $timestamps = false;

    public function grpname()
    {
        return $this->belongsTo(Group_name::class, 'group_id');
    }
    
  
    protected $table = 'container_group';
    protected $primaryKey = 'rec_id';
    protected $pk = 'rec_id';
}
