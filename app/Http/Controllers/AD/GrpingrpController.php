<?php

namespace App\Http\Controllers\AD;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\AD\GroupinGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GrpingrpController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store', 'multipleAdd']]);
        $this->middleware('role:delete', ['only' => ['destroy', 'multipleDelete']]);
    }

    private $m = GroupinGroup::class;
    private $pk = ['object_guid_parent'];

    public function index()
    {
        return GroupinGroup
            ::orderBy('object_guid_parent', 'asc')
            ->select('object_guid_parent')
            ->distinct('object_guid_parent')
            ->with('parentdetail')
           ->with('members', 'members.childdetail')
            ->get();
    }

    public function show($id)
    {
        //usergrp    parentdetail   childdetail
        $GroupinGroupInfo = GroupinGroup
            ::where('object_guid_parent', $id)
            ->with('members', 'members.childdetail')
            ->first();
            if($GroupinGroupInfo == null){
                return [];
            }
        $GroupinGroupInfo->members->makeHidden('object_guid_parent');
        return $GroupinGroupInfo->makeHidden(['object_guid_child']);
    }
    public function destroy(Request $request, GroupinGroup $model)
    {
        
        $model->where('object_guid_parent', '=', $request->get('object_guid_parent'))
        ->where('object_guid_parent', '=', $request->get('object_guid_parent'))->delete();
     
        return ['status' => 0]; 
    }
    public function multipleAdd(Request$request, GroupinGroup $model)
    {
        $items = $request->get('items');

        $model::insert($items);
        return ['status' => 0]; 

    }
    public function store(Request $request)
    {
        return $this->rStore($this->m, $request, 'object_guid_parent');
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
    public function search(Request $request)
    {
        $columns = ['object_guid_parent', 'object_guid_parent'];

        $model = GroupinGroup
            ::orderBy('object_guid_parent', 'asc')
            ->select('object_guid_parent')
            ->distinct('object_guid_parent')
                  ->with('members', 'members.childdetail');


        return ModelTreatment::getAsyncData($model, $request, $columns, 'ad', 'group_in_group', 'object_guid_parent', 'ASC');
    }
  
    public function itemsList($mode, $search = '')
    {
        $group_in_group = DB::table('group_in_group')
            ->select('object_guid_parent', DB::raw("CONCAT(grp_name, ' ', domin) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'object_guid_parent') {
            $group_in_group = $group_in_group->where('object_guid_parent', $search);
        } else if ($mode == 'phrase') {
            $group_in_group = $group_in_group->where(DB::raw("CONCAT(grp_name, ' ', domin)"), 'like', '%' . $search . '%');
        }
        return $group_in_group->take(100)->get();
    }
}
