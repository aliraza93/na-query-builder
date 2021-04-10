<?php

namespace App\Models\AD;

use Illuminate\Database\Eloquent\Model;


class AD_Traffic extends Model
{
    protected $connection = 'pgsql3';
    public $timestamps = false;
    protected $guarded = [];

    protected $table = 'traffic_log';
    protected $primaryKey = 'audit_id';
    protected $pk = 'audit_id';
}
