<?php

namespace App\Http\Controllers\AD;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\AD\ObjectinContainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AD\ObjectType;
class ObjectincontainerController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store', 'multipleAdd']]);
        $this->middleware('role:delete', ['only' => ['destroy','multipleDelete', 'custdestroy']]);
    }

    private $m = ObjectinContainer::class;
    private $pk = ['ts_id_parent'];

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
            ->where("subtype", "Container")
            ->with('containerinfo')
            ->with('computersincontainer', 'computersincontainer.computerdetail')
            ->with('usersincontainer', 'usersincontainer.userdetail')
            ->with('groupsincontainer', 'groupsincontainer.grpdetail')
            ->with('containerincontainer', 'containerincontainer.containerdetail')
            ->first();

        if ($Object_container == null) {
            return [];
        } 
        return $Object_container;
    }
    public function destroy(Request $request, ObjectinContainer $model)
    {

        $model->where('ts_id_child', '=', $request->get('ts_id_child'))
        ->where('ts_id_parent', '=', $request->get('ts_id_parent'))->delete();
     
        return ['status' => 0]; 
    }
    public function custdestroy(Request $request, ObjectinContainer $model)
    {
        // return ['status' => 0,"1"=>$request->get('ts_id_parent')];
       
        $model->where('ts_id_child', '=', $request->get('ts_id_child'))
        ->where('ts_id_parent', '=', $request->get('ts_id_parent'))->delete();
        return ['status' => 0];
    }
    public function multipleAdd(Request$request, ObjectinContainer $model)
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

                $model::where('ts_id_parent', $item['ts_id_parent'])
                ->where('ts_id_child', $item['ts_id_child'])
                ->delete();
            }
        } else {
            return ' Invaild Request!';
        }



        return ['status' => 0];
    }
    public function store(Request $request)
    {
        return $this->rStore($this->m, $request, 'ts_id_parent');
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
            ->select('ts_id_parent', DB::raw("CONCAT(grp_name, ' ', domin) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'ts_id_parent') {
            $traffic_source_contains = $traffic_source_contains->where('ts_id_parent', $search);
        } else if ($mode == 'phrase') {
            $traffic_source_contains = $traffic_source_contains->where(DB::raw("CONCAT(grp_name, ' ', domin)"), 'like', '%' . $search . '%');
        }
        return $traffic_source_contains->take(100)->get();
    }
}
