<?php

namespace App\Models\AD;

use Illuminate\Database\Eloquent\Model;


/*
SELECT start_time, duration, client_ip, req_type, bytes_size, "req_method ", status_code, url_add, user_auth
, timeout, handel_code, host_ip, content_type, id
	FROM public.squid_log;
*/

class Squidlog extends Model
{

    protected $connection = 'pgsql2';
    public $timestamps = false;
    protected $fillable = [
        'time',
        'elapsed',
        'remotehost',
        'response_code',
        'cache_hit_code',
        'bytes',
        'method',
        'url',
        'rfc931',
        'type',
        'peerstatus',
        'peerhost',
    ];

    public static $validator = [
        'time' => 'required|string',
        'elapsed' => 'string',
        'remotehost' => 'string',
        'response_code' => 'string',
        'cache_hit_code' => 'string',
        'bytes' => 'string',
        'method' => 'string',

        'url' => 'string',
        'rfc931' => 'string',
        'type' => 'string',
        'peerstatus' => 'string',
        'peerhost' => 'string',


    ];
    //SELECT "time", elapsed, remotehost, response_code, cache_hit_code, bytes, method, url, rfc931, type, peerstatus, peerhost


    public function getAddressAttribute()
    {
        $street = $this->street;
        $house = $this->house_number;
        $apartment = $this->apartment_number;
        return preg_replace('/\s+/', ' ', $street . " " . $house . ($apartment != null ? " lok. " . $apartment : ""));
    }

    protected $table = 'squid_log';
    protected $primaryKey = 'id';
    protected $pk = 'id';
}
