<?php

namespace App\Models\AD;

use Illuminate\Database\Eloquent\Model;


class AD_LdapConnection extends Model
{
    protected $connection = 'pgsql3';
    public $timestamps = false;
    protected $guarded = [];
    
    public static $validator = [
        'schema' => 'required|string',
        'account_prefix' => 'nullable|string',
        'account_suffix' => 'nullable|string',
        'hosts' => 'required|string',
        'port' => 'required|integer',
        'timeout' => 'required|integer',
        'base_dn' => 'required|string',
        'username' => 'required|string',
        'password' => 'required|string',
        'filter_include' => 'nullable|string',
        'filter_exclude' => 'nullable|string',
        'follow_referrals' => 'boolean',
        'use_ssl' => 'boolean',
        'use_tls' => 'boolean',
        'highest_committed_usn' => 'integer',
        'custom_options' => 'nullable|json',
    ];

    protected $table = 'ldap_connection';
    protected $primaryKey = 'schema';
    protected $pk = 'schema';
    protected $keyType = 'string';
}
