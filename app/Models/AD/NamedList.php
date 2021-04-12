<?php

namespace App\Models\AD;

use Illuminate\Database\Eloquent\Model;

use App\Models\AD\NamedListentry;

class NamedList extends Model
{
  
    protected $connection = 'pgsql';
    public $timestamps = false;
    protected $fillable = [
        'list_title',
        ];

    public static $validator = [
        'list_title' => 'required|string',
        
    ];

    public function members()
    {
        return $this->hasMany(NamedListentry::class, 'list_id', 'list_id');
    }
  
   
   

    protected $table = 'named_list';
    protected $primaryKey = 'list_id';
    protected $pk = 'list_id';
    

}
