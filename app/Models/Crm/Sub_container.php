<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;
use App\Models\Crm\Container;



class Sub_container extends Model
{
    protected $fillable = ['sub_container_id', 'container_id'];

    public static $validator = [
        'sub_container_id' => 'required|string',
        'container_id' => 'required|string',
    ];

    public $timestamps = false;

    public function subname()
    {
        return $this->belongsTo(Container::class, 'sub_container_id');
    }
    
  
    protected $table = 'container_sub';
    protected $primaryKey = 'rec_id';
    protected $pk = 'rec_id';
}
