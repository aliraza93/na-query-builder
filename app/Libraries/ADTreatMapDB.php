<?php

namespace App\Libraries;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
trait ADTreatMapDB
{
    public static function mapuser($user)
    {
        $fillable = [
            'object_guid',
            'common_name',
            'surname',
            'given_name',
            'sam_account_name',
            'physical_delivery_office_name',
            'telephone_number',
            'email',
            'department',
            'obj_dist_name',
            'last_logon',
            'logon_count',
            'user_principal_name',
            'is_enabled',
            'when_created',
            'when_changed',


        ];

        $lastlogon = $user['lastlogon'][0];
        $win_time = $lastlogon;
        $unix_time = round($win_time / 10000000) - 11644477200;
        $lastlogon = date("Y-m-d H:i:s.Z", $unix_time);


        $mapuser = [
            "object_guid" => $user->getConvertedGuid(),
            "common_name" => $user->getDisplayName(),
            "physical_delivery_office_name" => $user->getPhysicalDeliveryOfficeName(),
            "telephone_number" => $user->getTelephoneNumber(),
            "email" =>  "{" . $user->getEmail() . "}",
            "department" =>  $user->getDepartment(),
            "user_principal_name" =>  $user->getUserPrincipalName(),
            "last_logon" => $lastlogon,
            "obj_dist_name" => $user->getDnBuilder()->get(),
            "sam_account_name" => $user->getAccountName(),
            "given_name"   => $user->getFirstName(),
            "surname"   => $user->getAccountName(),
            "logon_count" => $user['logoncount'][0],
            

        ];
        return $mapuser;
    }

    public static function mstore($requests,$model,$pk)
    {
        try {


            //  $db = DB::connection('pgsql3');
            if (is_array($requests)) {
            foreach ($requests as  $arrays) {
                    if (is_array($arrays)) {
                        foreach ($arrays as $request) {
                            $fields = $model::$validator;
                            $request = json_decode(json_encode($request), true);

                           
                            if (is_array($pk)) {
                                $newvalues = $model::where($pk[0], $request[$pk[0]])->where($pk[1], $request[$pk[1]])->first();
                            } else {
                                $newvalues = $model::where($pk, $request[$pk])->first();
                            }
                   
                            if ($newvalues !== null) {
                                //  return $newvalues;
                                $insert = [];
                                foreach ($fields as $key => $value) {
                                    if (array_key_exists($key, $request)) {
                                        $insert[$key] = $request[$key];
                                    }
                                }
                                //return ['status' => $insert, $pk, $request[$pk]];

                                $newvalues->update($insert);
                            } else {
                                $insert = [];
                                foreach ($fields as $key => $value) {
                                    if (array_key_exists($key, $request)) {
                                        $insert[$key] = $request[$key];
                                    }
                                }
                                $newvalues = $model::create($insert);
                            }
                        }
                    }
            }
                  

            }


            return ['status' => 0];
        
        }
        
        catch (\Exception $e) {
            return response()->json(['status' => -2, "msg" => $e->getMessage()], 200);
           // return ['status' => -2, 'msg' => $e->getMessage()];

        }
    }
    public static function mstorewithoutupdate($requests, $model, $pk)
    {
        try {


            //  $db = DB::connection('pgsql3');
            if (is_array($requests)) {
                foreach ($requests as  $arrays) {
                    if (is_array($arrays)) {
                        foreach ($arrays as $request) {
                            $fields = $model::$validator;
                            $request = json_decode(json_encode($request), true);
                           
                            if (is_array($pk)) {
                                $newvalues = $model::where($pk[0], $request[$pk[0]])->where($pk[1], $request[$pk[1]])->first();
                            } else {
                                $newvalues = $model::where($pk, $request[$pk])->first();
                            }

                            if ($newvalues !== null) {
                                
                            } else {
                                $insert = [];
                                foreach ($fields as $key => $value) {
                                    if (array_key_exists($key, $request)) {
                                        $insert[$key] = $request[$key];
                                    }
                                }
                                $newvalues = $model::create($insert);
                                
                                
                            }
                        }
                    }
                }
            }



            return ['status' => 0];
            
        } catch (\Exception $e) {
            return response()->json(['status' => -2,"msg" => $e->getMessage()], 200);
            //return ['status' => -2, 'msg' => $e->getMessage()];
        }
    }

public static function ldapconect($config){
        $ad = new \Adldap\Adldap();
        $ad->addProvider($config);
        $ad->addProvider($config, 'default');
        $ad->setDefaultProvider('default');
        try {
           $ad->connect();
            
             $provider = $ad->getDefaultProvider();
            return ['status' => 0,'provider'=>$provider,'ldap'=>$ad];

        
        } catch (\Adldap\Auth\BindException $e) {
            
            return ['status' => -2, 'msg' => $e->getMessage()];
         
        }

}

    
}
