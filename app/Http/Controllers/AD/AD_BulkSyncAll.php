<?php

namespace App\Http\Controllers\AD;

use App\Http\Controllers\Controller;
use App\Models\AD\AD_Groups;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AD\AD_Ous;
use App\Models\AD\AD_Computer;
use App\Models\AD\AD_Users;
use App\Models\AD\GroupinGroup;
use App\Models\AD\UsersinGroup;
use App\Models\AD\ComputersinGroup;



class AD_BulkSyncAll extends Controller
{
    public function __construct()
    {
        set_time_limit(1200);
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store','mstore', 'multipleAdd']]);
        $this->middleware('role:update', ['only' => ['update', 'multipleUpdate']]);
        $this->middleware('role:delete', ['only' => ['destroy']]);
    }
    public function index()
    {
        return ['status' => 0];
        
    }

   
  
    public function syncall(Request $request)
    {
        

        try{
        $db = DB::connection('pgsql3');
        
        $allous = $request->get('ous');
        if (is_array($allous)) {
                $runssync = $this->allous($allous);
                if (is_array($runssync)) {
                    if ($runssync['status'] != 0) {
                        return  $runssync;
                    }
                } else {
                    return  $runssync;
                }
        }

        $allgroups = $request->get('groups');
        if(is_array($allgroups)){
                $runssync =  $this->allgroups($allgroups);
                if (is_array($runssync)) {
                    if ($runssync['status'] != 0) {
                        return  $runssync;
                    }
                } else {
                    return  $runssync;
                }
        }
       

        $allcomputers = $request->get('computers');
        if (is_array($allcomputers)) {
                $runssync =  $this->allcomputers($allcomputers);
                if (is_array($runssync)) {
                    if ($runssync['status'] != 0) {
                        return  $runssync;
                    }
                } else {
                    return  $runssync;
                }
        }
        
      
        $allusers = $request->get('users');
        if (is_array($allusers)) {
            $runssync=  $this->allusers($allusers);
       
            if (is_array($runssync)) {
                if ($runssync['status'] != 0) {
                    return  $runssync;
                }
            } else {
                return  $runssync;
            }
        }
       
       
       $allgrpmember = $request->get('grpmember');
       if ($allgrpmember !== null) {
           $runssync =   $this->syncmembersmultiple($allgrpmember, $db);
           if (is_array($runssync)) {
               if ($runssync['status'] != 0) {
                   return  $runssync;
               }
           } else {
               return  $runssync;
           }
       }
        return ['status' => 0];
        } catch (\Exception $e) {
            $results =  ['status' => -2, 'msg' => $e->getMessage()];

            return $results;

            // something went wrong
        }

    }
    public function allous($items)
    {
      try{

        foreach ($items as $item) {
            $pk = ['object_guid'   => $item['object_guid']];
            AD_Ous::updateOrCreate($pk, $item);
        }
        return ['status' => 0];
        } catch (\Exception $e) {
            $results =  ['status' => -2, 'msg' => $e->getMessage()];

            return $results;

        }
    }
    public function allgroups($items)
    {
          try {
              foreach ($items as $item) {
                  $pk = ['object_guid'   => $item['object_guid']];
                  AD_Groups::updateOrCreate($pk, $item);
              }
              return ['status' => 0];
          }catch (\Exception $e) {
            $results =  ['status' => -2, 'msg' => $e->getMessage()];

            return $results;

        }
    }
    public function allusers($items)
    {
       
        try {
            foreach ($items as $item) {
                $pk = ['object_guid'   => $item['object_guid']];
                $usermail = $item['email_addresses'];
                if ($usermail === null || $usermail === '') {
                    $item['email_addresses'] = null;
                } else {
                    $item['email_addresses'] = "{" . preg_quote($usermail) . "}";
                }

                AD_Users::updateOrCreate($pk, $item);
            }
            return ['status' => 0];
        }catch (\Exception $e) {
            $results =  ['status' => -2, 'msg' => $e->getMessage()];

            return $results;

        }
    }
    public function allcomputers($items)
    {
        try {

            foreach ($items as $item) {
                $pk = ['object_guid'   => $item['object_guid']];
                
                AD_Computer::updateOrCreate($pk, $item);
            }
            return ['status' => 0];
        } catch (\Exception $e) {
            $results =  ['status' => -2, 'msg' => $e->getMessage()];

            return $results;

        }
    }
    public function syncmembersmultiple($requestsync, $db)
    {
        try {
        $requestitems = $requestsync;


        if ($requestitems === null) {
            return  ['status' => -2, 'error' => 'items not found'];
        }
        if (!is_array($requestitems)) {
            return  ['status' => -2, 'error' => 'items not array'];
        }

        $report = array();
       
            /// <-!----------groups
      
            foreach ($requestitems as $requestitem) {
                $group_id =  $requestitem['grp_parent_id'];
                $users = $requestitem['users'];
                $computers = $requestitem['computers'];
                $groups =  $requestitem['groups'];
                if ($group_id !== null) {
                    $grptable =  $db->table('ad_group')->select('object_guid')->where('object_guid', '=', $group_id)->first();
                    if ($grptable !== null) {
                   
                    if (is_array($groups)) {
                        $mapgrp = array();
                        $i = 0;
                        foreach ($groups as $objg) {
                            $mapgrp[$i]["object_guid_parent"] = $group_id;
                            $mapgrp[$i]["object_guid_child"] = $objg;
                            $i++;
                        }
                        GroupinGroup::where('object_guid_parent', '=', $group_id)->delete();
                        foreach ($mapgrp as $item) {
                            $db->insert(
                                'insert into group_in_group (object_guid_parent, object_guid_child) 
                        values (?, ?) ON CONFLICT   
                        (object_guid_parent, object_guid_child) DO NOTHING ',
                                [$item['object_guid_parent'], $item['object_guid_child']]
                            );
                        }
                    }
                    /// <-!----------users
                    $mapusers = array();
                    $i = 0;
                    if (is_array($users)) {
                        foreach ($users as $obju) {
                            $mapusers[$i]["object_guid_parent"] = $group_id;
                            $mapusers[$i]["object_guid_child"] = $obju;
                            $i++;
                        }
                        UsersinGroup::where('object_guid_parent', '=', $group_id)->delete();
                        foreach ($mapusers as $item) {
                            $db->insert(
                                'insert into user_in_group (object_guid_parent, object_guid_child) 
                        values (?, ?) ON CONFLICT   
                        (object_guid_parent, object_guid_child) DO NOTHING ',
                                [$item['object_guid_parent'], $item['object_guid_child']]
                            );
                        }
                    }
                    /// <-!----------computers
                    $mapcomputers = array();
                    $i = 0;
                    if (is_array($computers)) {
                        foreach ($computers as $objc) {
                            $mapcomputers[$i]["object_guid_parent"] = $group_id;
                            $mapcomputers[$i]["object_guid_child"] = $objc;
                            $i++;
                        }
                        ComputersinGroup::where('object_guid_parent', '=', $group_id)->delete();
                        foreach ($mapcomputers as $item) {
                            $db->insert(
                                'insert into computer_in_group (object_guid_parent, object_guid_child) 
                        values (?, ?) ON CONFLICT   
                        (object_guid_parent, object_guid_child) DO NOTHING ',
                                [$item['object_guid_parent'], $item['object_guid_child']]
                            );
                        }
                     }
                    }
                }
            }


          
            $report[] = "sync-groups-ok";
            $report[] = "sync-users-ok";
            $report[] = "sync-computers-ok";
            return ["status" => "0", "actions" => $report];
        } catch (\Exception $e) {
            $results = ['status' => -2, 'msg' => $e->getMessage()];
            return $results;
        }
    }



    

}
