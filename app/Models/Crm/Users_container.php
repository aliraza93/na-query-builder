<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;
use App\Models\Crm\User_name;


class Users_container extends Model
{
    protected $fillable = ['container_id', 'user_id'];

    public static $validator = [
        'container_id' => 'required|string',
        'user_id' => 'required|string',
    ];

    public $timestamps = false;

    public function username()
    {
        return $this->belongsTo(User_name::class, 'user_id');
    }
    
  
    protected $table = 'container_users';
    protected $primaryKey = 'rec_id';
    protected $pk = 'rec_id';
}
