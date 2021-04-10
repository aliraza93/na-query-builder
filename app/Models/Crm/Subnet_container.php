<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;
use App\Models\Crm\Container;


class Subnet_container extends Model
{
    protected $fillable = ['container_id', 'ip_id'];

    public static $validator = [
        'container_id' => 'required|string',
        'ip_id' => 'required|string',
    ];

    public $timestamps = false;

    public function containername()
    {
        return $this->belongsTo(Container::class, 'container_id');
    }
    
  
    protected $table = 'container_ips';
    protected $primaryKey = 'rec_id';
    protected $pk = 'rec_id';
}
