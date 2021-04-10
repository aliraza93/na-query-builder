<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;
use App\Models\Crm\Group_name;


class Grp_user extends Model
{
    protected $fillable = ['group_id', 'user_id'];

    public static $validator = [
        'group_id' => 'required|string',
        'user_id' => 'required|string',
    ];

    public $timestamps = false;

    public function grpname()
    {
        return $this->belongsTo(Group_name::class, 'group_id');
    }
    protected $table = 'user_group';
    protected $primaryKey = 'rec_id';
    protected $pk = 'rec_id';
}
