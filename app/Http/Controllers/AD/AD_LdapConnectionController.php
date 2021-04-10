<?php

namespace App\Http\Controllers\AD;

use App\Http\Controllers\Controller;
use App\Models\AD\AD_LdapConnection;
use Illuminate\Http\Request;
use App\Models\Libraries\ADTreatMapDB;

class AD_LdapConnectionController extends Controller
{
  public function __construct()
  {
    $this->middleware('role:read', ['only' => ['index','testldap']]);
    $this->middleware('role:insert', ['only' => ['store']]);
    $this->middleware('role:update', ['only' => ['update']]);
  }

  private $m = AD_LdapConnection::class;
  private $pk = 'schema';

  public function index()
  {
    return AD_LdapConnection
      ::first();
  }

  public function update($prefix, Request $request, AD_LdapConnection $model)
  {
    $ldapconection = $this->testldap($request);
   
    if (is_array($ldapconection)) {
      
      if ($ldapconection['status'] != 0) {
        return  $ldapconection;
      }
    } else {
      return  $ldapconection;
    }
    return $this->sUpdate($this->m, $model, $request->all(), $this->pk, $prefix);
  }

  public function store(Request $request)
  {
    $ldapconection = $this->testldap($request);
    if (is_array($ldapconection)) {

      if ($ldapconection['status'] != 0) {
        return  $ldapconection;
      }
    } else {
      return  $ldapconection;
    }
    if ($this->m::count() === 1) {
      return;
    }

    return $this->rStore($this->m, $request, $this->pk);
  }
  public function testldap(Request $request)
  {
    if( $request->get('follow_referrals') === 1 || $request->get('follow_referrals') === true){
      $follow_referrals = true;
    }else{
      $follow_referrals = false;
    }
    if ($request->get('use_ssl') === 1 || $request->get('use_ssl') === true) {
      $use_ssl = true;
    } else {
      $use_ssl = false;
    }
    if ($request->get('use_tls') === 1 || $request->get('use_tls') === true) {
      $use_tls = true;
    } else {
      $use_tls = false;
    }
    $config = [];
    $config['hosts'] =   [$request->get('hosts')];
    $config['base_dn'] =   $request->get('base_dn');
    $config['username'] =    $request->get('username');
    $config['password'] =   $request->get('password');
    $config['account_prefix'] =   $request->get('account_prefix');
    $config['account_suffix'] =   $request->get('account_suffix');
    $config['port'] =    $request->get('port');
    $config['follow_referrals'] =   $follow_referrals;
    $config['use_ssl'] =    $use_ssl;
    $config['use_tls'] =    $use_tls;
    $config['version'] =  3;
    $config['timeout'] =   $request->get('timeout');
    $ldapconection =   ADTreatMapDB::ldapconect($config);
    if (is_array($ldapconection)) {
      if ($ldapconection['status'] == 0) {
        return ['status' => 0, 'msg' => 'Connection Successful'];
      }
      if ($ldapconection['status'] != 0) {
        return  $ldapconection;
      }
    } else {
      return  $ldapconection;
    }
  }

}
