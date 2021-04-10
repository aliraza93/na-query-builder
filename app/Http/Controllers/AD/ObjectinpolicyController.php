<?php

namespace App\Http\Controllers\AD;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\AD\ObjectinPolicy;
use App\Models\AD\Policies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AD\ObjectType;
class ObjectinpolicyController  extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show','search', 'custget']]);
        $this->middleware('role:insert', ['only' => ['store', 'multipleAdd']]);
        $this->middleware('role:update', ['only' => ['update', 'multipleUpdate']]);
        $this->middleware('role:delete', ['only' => ['destroy','multipleDelete', 'custdestroy']]);
    }

    private $m = ObjectinPolicy::class;
    private $pk = ['policy_id'];

    public function index()
    {
        //Container
        return  ObjectType
            ::orderBy('ts_id', 'asc')
            ->where("subtype", "Container")
            ->get();
   
    }

    public function show($id)
    {
        //usergrp    grpdetail   userdetail
        $Object_container = ObjectType
            ::where('ts_id', $id)
        /*    ->where("subtype", "Container")
            ->with('containerinfo')
            ->with('computersincontainer', 'computersincontainer.computerdetail')
            ->with('usersincontainer', 'usersincontainer.userdetail')
            ->with('groupsincontainer', 'groupsincontainer.grpdetail')
            ->with('containerincontainer', 'containerincontainer.containerdetail')*/
            ->first();

        if ($Object_container == null) {
            return [];
        } 
        return $Object_container;
    }
    public function custget(Request $request, ObjectinPolicy $model)
    {
        $Policy_objInfo = ObjectinPolicy
            ::where('ts_id', '=', $request->get('ts_id'))
            ->where('policy_id', '=', $request->get('policy_id'))
            ->first();
        return $Policy_objInfo;
    }
    public function update($prefix, Request $request, ObjectinPolicy $model)
    {
        //  return $this->rUpdate($this->m, $model, $request->all(), $this->pk);
        if ($request->get('enforced_flag') == null || $request->get('enforced_flag') == '') {
            $request->request->set('enforced_flag', false);
        }
        return $this->compsetUpdate(
            $this->m,
            $model,
            $request->all(),
            [ 'ts_id','policy_id'],
            $prefix
        );
    }
    public function destroy(Request $request, ObjectinPolicy $model)
    {

        $model->where('ts_id', '=', $request->get('ts_id'))
        ->where('policy_id', '=', $request->get('policy_id'))->delete();
     
        return ['status' => 0]; 
    }
    public function custdestroy(Request $request, ObjectinPolicy $model)
    {
        // return ['status' => 0,"1"=>$request->get('policy_id')];
       
        $model->where('ts_id', '=', $request->get('ts_id'))
        ->where('policy_id', '=', $request->get('policy_id'))->delete();
        return ['status' => 0];
    }
    public function multipleAdd(Request$request, ObjectinPolicy $model)
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

                $model::where('policy_id', $item['policy_id'])
                ->where('ts_id', $item['ts_id'])
                ->delete();
            }
        } else {
            return ' Invaild Request!';
        }



        return ['status' => 0];
    }
    public function store(Request $request)
    {
        if ($request->get('enforced_flag') == null || $request->get('enforced_flag') == '') {
            $request->request->set('enforced_flag', false);

        }

        $policy = Policies::where('policy_id', $request->get('policy_id'))->first();
        $request->request->add(['priority_policy' =>$policy['priority']]);
        return $this->rStore($this->m, $request, 'policy_id');
    }
    public function search(Request $request)
    {
        $columns = ['ts_id', 'subtype'];

        $model = ObjectType
            ::orderBy('ts_id', 'asc')
            ->where("subtype", "Container")
            ->with('containerinfo');

        return ModelTreatment::getAsyncData($model, $request, $columns, 'ad', 'traffic_source', 'ts_id', 'ASC');
    }
  
    public function itemsList($mode, $search = '')
    {
        $traffic_source_contains = DB::table('traffic_source_contains')
            ->select('policy_id', DB::raw("CONCAT(grp_name, ' ', domin) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'policy_id') {
            $traffic_source_contains = $traffic_source_contains->where('policy_id', $search);
        } else if ($mode == 'phrase') {
            $traffic_source_contains = $traffic_source_contains->where(DB::raw("CONCAT(grp_name, ' ', domin)"), 'like', '%' . $search . '%');
        }
        return $traffic_source_contains->take(100)->get();
    }
}
