<?php

namespace App\Models\AD;

use Illuminate\Database\Eloquent\Model;

class PolicySetting extends Model
{
  
    protected $connection = 'pgsql3';
    public $timestamps = false;
    protected $guarded = [];
    public static $validator = [
        'unique_row' => 'required|boolean',
        'policy_id_default' => 'nullable|integer',
        'policy_default_is_allow_flag' => 'required|boolean',
        'log_days_preserve_allowed' => 'required|integer',
        'log_days_preserve_rejected' => 'required|integer',
        'schemaless' => 'nullable|json',
    ];

    protected $table = 'admin_configuration';
    protected $primaryKey = 'unique_row';
    protected $pk = 'unique_row';
    protected $keyType = 'boolean';
}
