<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;
use App\Models\Crm\User_name;


class Users_grp extends Model
{
    protected $fillable = ['group_id', 'user_id'];

    public static $validator = [
        'group_id' => 'required|string',
        'user_id' => 'required|string',
    ];

    public $timestamps = false;

    public function username()
    {
        return $this->belongsTo(User_name::class, 'user_id');
    }
    protected $table = 'user_group';
    protected $primaryKey = 'rec_id';
    protected $pk = 'rec_id';
}
