<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;
use App\Models\Crm\Sub_net;


class Container_subnet extends Model
{
    protected $fillable = ['container_id', 'ip_id'];

    public static $validator = [
        'container_id' => 'required|string',
        'ip_id' => 'required|string',
    ];

    public $timestamps = false;

    public function ipadrr()
    {
        return $this->belongsTo(Sub_net::class, 'ip_id');
    }
    
  
    protected $table = 'container_ips';
    protected $primaryKey = 'rec_id';
    protected $pk = 'rec_id';
}
