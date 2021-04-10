<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;



class Ip_user extends Model
{
    protected $fillable = ['ip_addr', 'user_id'];

    public static $validator = [
        'ip_addr' => 'required|string',
        'user_id' => 'required|string',
    ];

    public $timestamps = false;


    
  
    protected $table = 'ip_mapping';
    protected $primaryKey = 'rec_id';
    protected $pk = 'rec_id';
}
