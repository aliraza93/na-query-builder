<?php

namespace App\Http\Controllers\AD;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\AD\UsersinGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UseringrpController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store', 'multipleAdd']]);
        $this->middleware('role:delete', ['only' => ['destroy', 'multipleDelete']]);
    }

    private $m = UsersinGroup::class;
    private $pk = ['object_guid_parent', 'object_guid_child'];

    public function index()
    {
        return UsersinGroup
            ::orderBy('object_guid_child', 'asc')
            ->with('memberof', 'memberof.grpdetail')
            ->get();
    }

    public function show($id)
    {
        //usergrp    grpdetail   userdetail
        $Users_grpInfo = UsersinGroup
            ::where('object_guid_child', $id)
            ->with('memberof', 'memberof.grpdetail')
            ->first();

        if ($Users_grpInfo == null) {
            return [];
        } 
        return $Users_grpInfo;
    }
    public function destroy(Request $request, UsersinGroup $model)
    {
        
        $model->where('object_guid_child', '=', $request->get('user'))
        ->where('object_guid_parent', '=', $request->get('group'))->delete();
     
        return ['status' => 0]; 
    }
    public function multipleAdd(Request$request, UsersinGroup $model)
    {
        $items = $request->get('items');

        $model::insert($items);
        return ['status' => 0]; 

    }
    public function multipleDelete(Request $request)
    {
        $model = $this->m;
        $items = $request->get('items');
        if (is_array($items)) {
            foreach ($items as $item) {

                $model::where('object_guid_parent', $item['object_guid_parent'])
                ->where('object_guid_child', $item['object_guid_child'])
                ->delete();
            }
        } else {
            return ' Invaild Request!';
        }



        return ['status' => 0];
    }
    public function store(Request $request)
    {
        return $this->rStore($this->m, $request, 'object_guid_parent');
    }
    public function search(Request $request)
    {
        $columns = ['object_guid_parent', 'object_guid_child'];

        $model = UsersinGroup
            ::orderBy('object_guid_child', 'asc')
            ->with('memberof', 'memberof.grpdetail');

        return ModelTreatment::getAsyncData($model, $request, $columns, 'ad', 'user_in_group', 'object_guid_child', 'ASC');
    }
  
    public function itemsList($mode, $search = '')
    {
        $user_in_group = DB::table('user_in_group')
            ->select('object_guid_parent', DB::raw("CONCAT(grp_name, ' ', domin) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'object_guid_parent') {
            $user_in_group = $user_in_group->where('object_guid_parent', $search);
        } else if ($mode == 'phrase') {
            $user_in_group = $user_in_group->where(DB::raw("CONCAT(grp_name, ' ', domin)"), 'like', '%' . $search . '%');
        }
        return $user_in_group->take(100)->get();
    }
}
