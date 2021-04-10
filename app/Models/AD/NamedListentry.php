<?php

namespace App\Models\AD;

use Illuminate\Database\Eloquent\Model;


class NamedListentry extends Model
{
  //1)Regex 2)Wildcard 3)String
    protected $connection = 'pgsql3';
    public $timestamps = false;
    protected $fillable = [
        'match_string',
        'list_id',
        'operator_code'
        ];

    public static $validator = [
        'match_string' => 'required|string',
        'list_id' => 'required',
        'operator_code'=>'string',

        
    ];
  
   
   

    protected $table = 'named_list_entry';
    protected $primaryKey = 'list_entry_id';
    protected $pk = 'list_entry_id';
    

}
