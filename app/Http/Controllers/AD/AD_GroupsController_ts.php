<?php

namespace App\Http\Controllers\AD;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\AD\AD_Groups;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AD_GroupsController_ts extends Controller
{
    public function __construct()
    {
        // $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        // $this->middleware('role:insert', ['only' => ['store','mstore']]);
        // $this->middleware('role:update', ['only' => ['update', 'multipleUpdate']]);
        // $this->middleware('role:delete', ['only' => ['destroy']]);
    }

    private $m = AD_Groups::class;
    private $pk = 'ts_id';

    public function index()
    {
        return AD_Groups::orderBy('common_name', 'asc')->with('memberof', 'memberof.parentdetail')->get();
    }

    public function show($id)
    {
        $AD_GroupsInfo = AD_Groups
            ::where('ts_id', $id)
            ->with('memberof', 'memberof.parentdetail')
            ->with('members_grps', 'members_grps.childdetail')
            ->with('members_users', 'members_users.userdetail')
            ->with('members_computers', 'members_computers.computerdetail')
            ->with('grpsincontainers', 'grpsincontainers.parentcontainer')
            ->with('grpinous', 'grpinous.parentous')


            ->first();
        if ($AD_GroupsInfo == null) {
            return [];
        }
        $AD_GroupsInfo['policygrp'] = ModelTreatment::getpolicygrps($id);
        

        return $AD_GroupsInfo;
    }

    public function update($prefix, Request $request, AD_Groups $model)
    {
        //  return $this->rUpdate($this->m, $model, $request->all(), $this->pk);
        return $this->sUpdate($this->m, $model, $request->all(), $this->pk, $prefix);
    }
    public function store(Request $request)
    {
        return $this->rStore($this->m, $request, $this->pk);
    }
    public function destroy($prefix, AD_Groups $model)
    {

        $model->destroy($prefix);

        return ['status' => 0];
    }
    public function search(Request $request)
    {
        $columns = [
            'ts_id',
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
                ->with('members', 'members.childdetail');

        } else {
            $model = AD_Groups
                ::select($columns);
        }

      

        return ModelTreatment::getAsyncData($model, $request, $columns, 'ad', 'ad_group', 'common_name', 'ASC');
    }
    public function itemsList($mode, $search = '')
    {
        $AD_Groups = DB::table('ad_group')
            ->select('ts_id', DB::raw("CONCAT(common_name, ' ', ts_id) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'ts_id') {
            $AD_Groups = $AD_Groups->where('ts_id', $search);
        } else if ($mode == 'phrase') {
            $AD_Groups = $AD_Groups->where(DB::raw("CONCAT(common_name, ' ', ts_id)"), 'like', '%' . $search . '%');
        }
        return $AD_Groups->take(100)->get();
    }
}
