<?php

namespace App\Http\Controllers\AD;

use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\AD\AD_Groups;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AD\GroupinGroup;
use App\Models\AD\UsersinGroup;
use App\Models\AD\ComputersinGroup;


class Full_SyncController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => [ 'fullsync']]);
    }

    private $m = AD_Groups::class;
    private $pk = 'object_guid';

   

  
    public function fullsync(Request $request)
    {

    $group_id = $request->get('grp_parent_id');
     $users = $request->get('users');
     $computers = $request->get('computers');
     $groups= $request->get('groups');
        if ($group_id === null) {
            return  ['status' => 1, 'error' => 'parent group required'];
        }
        $db = DB::connection('pgsql');
        $grptable=  $db->table('ad_group')->where('object_guid', '=', $group_id)->first();
        if($grptable === null){
          return  ['status' => 1, 'error' => 'parent group not found'];
        }
        $report= array();
        try {
            /// <-!----------groups
            
            $db->beginTransaction();
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
                         [$item['object_guid_parent'], $item['object_guid_child']]  );


                }
               
                $report[]= "sync-groups-ok";
        }
           /// <-!----------users
        $mapusers= array();
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
                $report[] = "sync-users-ok";

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
                $report[] = "sync-computers-ok";
        }

           
             $db->commit();
             return ["status"=>"0", "actions"=>$report];
        } catch (\Throwable $e) {
            $results =  ['status' => 1, 'error' =>  $e->getMessage()];
            $db->rollback();
            return $results;
        }

    }


    public function search(Request $request)
    {
        $columns = [
            'object_guid',
            'common_name',
            'obj_dist_name',
            'when_created',
            'when_changed',


        ];
     
        if ($request->has('detail') && $request->get('detail') == "all") {
            
            $model = AD_Groups
                ::orderBy('common_name', 'asc')
                ->with('memberof', 'memberof.parentdetail')
                ->with('members_grps', 'members_grps.childdetail')
                ->with('members_users', 'members_users.userdetail');
        } else {
            $model = AD_Groups
                ::orderBy('common_name', 'asc');
        }

      

        return ModelTreatment::getAsyncData($model, $request, $columns, 'ad', 'ad_group', 'common_name', 'ASC');
    }
    public function itemsList($mode, $search = '')
    {
        $AD_Groups = DB::table('ad_group')
            ->select('object_guid', DB::raw("CONCAT(common_name, ' ', object_guid) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'object_guid') {
            $AD_Groups = $AD_Groups->where('object_guid', $search);
        } else if ($mode == 'phrase') {
            $AD_Groups = $AD_Groups->where(DB::raw("CONCAT(common_name, ' ', object_guid)"), 'like', '%' . $search . '%');
        }
        return $AD_Groups->take(100)->get();
    }
}
