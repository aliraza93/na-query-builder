<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;



class ReportsDefine extends Model
{
    protected $fillable = [
        'report_name',
        'report_filter',

    ];
    public $timestamps = false;

    protected $appends = ['fullname'];
    public function getFullnameAttribute()
    {
       return [];
    }
   

    public static $validator = [
        'report_name' => 'required|string',
        'report_filter' => 'required',
       

    ];
    protected $table = 'reports';
    protected $primaryKey = 'id';
    protected $pk = 'id';
}
