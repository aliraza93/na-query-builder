<?php

namespace App\Http\Controllers\AD;

use App\Http\Controllers\Controller;
use Adldap\Adldap;
class authldabController extends Controller
{
  public function index()
    {
     $ad = new \Adldap\Adldap();

// Create a configuration array.
 $config = [
    'hosts'            => ['192.168.252.41'],
    'base_dn'          => 'dc=egirna,dc=local',
    'username'         => 'administrator@egirna.local',
    'password'         => 'egirna@123!@#!@#',

   // 'schema'           => \Adldap\Schemas\OpenLDAP::class,
    'account_prefix'   => '',
    'account_suffix'   => '',
    'port'             => 389,
    'follow_referrals' => false,
    'use_ssl'          => false,
    'use_tls'          => false,
    'version'          => 3,
    'timeout'          => 5,

    // Custom LDAP Options
   /* 'custom_options'   => [
        // See: http://php.net/ldap_set_option
        LDAP_OPT_X_TLS_REQUIRE_CERT => LDAP_OPT_X_TLS_HARD
    ]*/
];

$ad->addProvider($config);
  $ad->addProvider($config, 'my-connection');
        $ad->setDefaultProvider('my-connection');
    //    print_r(  $ad->getDefaultProvider());


try {
    $provider = $ad->connect();
    
    $results = $provider->search()->ous()->get();
    
    echo 'OUs:'."\r\n";
    echo '==============='."\r\n";
    foreach($results as $ou) {
        echo $ou->getDn()."\r\n";
    }
    
    echo "\r\n";
    
    $results = $provider->search()->users()->get();
    
    echo 'Users:'."\r\n";
    echo '==============='."\r\n";
    foreach($results as $user) {
        
        echo $user->getAccountName()."\r\n";
    }
    
    echo "\r\n";
    
    $results = $provider->search()->groups()->get();
    
    echo 'Groups:'."\r\n";
    echo '==============='."\r\n";
    foreach($results as $group) {
        echo $group->getCommonName().' | '.$group->getDisplayName()."\r\n";
    }



} catch (\Adldap\Auth\BindException $e) {

    echo 'Error: '.$e->getMessage()."\r\n";
}
    }
}

?>