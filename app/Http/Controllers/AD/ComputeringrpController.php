<?php

namespace App\Http\Controllers\AD;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\AD\ComputersinGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComputeringrpController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store', 'multipleAdd']]);
        $this->middleware('role:delete', ['only' => ['destroy', 'multipleDelete']]);
    }

    private $m = ComputersinGroup::class;
    private $pk = ['object_guid_parent', 'object_guid_child'];

    public function index()
    {
        return ComputersinGroup
            ::orderBy('object_guid_child', 'asc')
            ->with('memberof', 'memberof.grpdetail')
            ->get();
    }

    public function show($id)
    {
        //usergrp    grpdetail   userdetail
        $Com_grpInfo = ComputersinGroup
            ::where('object_guid_child', $id)
            ->with('memberof', 'memberof.grpdetail')
            ->first();

        if ($Com_grpInfo == null) {
            return [];
        } 
        return $Com_grpInfo;
    }
    public function destroy(Request $request, ComputersinGroup $model)
    {
        
        $model->where('object_guid_child', '=', $request->get('computer'))
        ->where('object_guid_parent', '=', $request->get('group'))->delete();
     
        return ['status' => 0]; 
    }
    public function multipleAdd(Request$request, ComputersinGroup $model)
    {
        $items = $request->get('items');

        $model::insert($items);
        return ['status' => 0]; 

    }
    public function multipleDelete(Request $request)
    {
        $model = $this->m;
        $items = $request->get('items');
        if(is_array($items)){
            foreach($items as $item){
                
                $model::where('object_guid_parent' ,$item['object_guid_parent'])
                ->where('object_guid_child', $item['object_guid_child'])
                ->delete();

            }


        }else{
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

        $model = ComputersinGroup
            ::orderBy('object_guid_child', 'asc')
            ->with('memberof', 'memberof.grpdetail');

        return ModelTreatment::getAsyncData($model, $request, $columns, 'ad', 'computer_in_group', 'object_guid_child', 'ASC');
    }
  
    public function itemsList($mode, $search = '')
    {
        $computer_in_group = DB::table('computer_in_group')
            ->select('object_guid_parent', DB::raw("CONCAT(grp_name, ' ', domin) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'object_guid_parent') {
            $computer_in_group = $computer_in_group->where('object_guid_parent', $search);
        } else if ($mode == 'phrase') {
            $computer_in_group = $computer_in_group->where(DB::raw("CONCAT(grp_name, ' ', domin)"), 'like', '%' . $search . '%');
        }
        return $computer_in_group->take(100)->get();
    }
}
