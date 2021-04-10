<?php

namespace App\Models\AD;

use Illuminate\Database\Eloquent\Model;


class AD_LogPreserve extends Model
{
    protected $connection = 'pgsql3';
    public $timestamps = false;
    protected $guarded = [];
    
    public static $validator = [
        'log_preserve_days_allowed' => 'required|integer',
        'log_preserve_days_rejected' => 'required|integer',
        'schemaless' => 'nullable|json',
    ];

    protected $table = 'configuration';
}
