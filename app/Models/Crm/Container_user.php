<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;
use App\Models\Crm\Container;


class Container_user extends Model
{
    protected $fillable = ['container_id', 'user_id'];

    public static $validator = [
        'container_id' => 'required|string',
        'user_id' => 'required|string',
    ];

    public $timestamps = false;

    public function contname()
    {
        return $this->belongsTo(Container::class, 'container_id');
    }
    
  
    protected $table = 'container_users';
    protected $primaryKey = 'rec_id';
    protected $pk = 'rec_id';
}
