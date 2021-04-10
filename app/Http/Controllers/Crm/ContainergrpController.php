<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\Crm\Container_grp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContainergrpController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store']]);
        $this->middleware('role:update', ['only' => ['update', 'multipleUpdate']]);
        $this->middleware('role:delete', ['only' => ['destroy']]);
    }

    private $m = Container_grp::class;
    private $pk = 'rec_id';

    public function index()
    {
        return Container_grp
            ::orderBy('container_id', 'asc')
            ->get();
    }

    public function show($id)
    {
        //usergrp
        $Container_grpInfo = Container_grp
            ::where('group_id', $id)
            ->first();
        return $Container_grpInfo;
    }
    public function destroy($prefix, Container_grp $model)
    {

        $model->destroy($prefix);
       
     
        return ['status' => 0]; 
     //   return $this->rDestroy($model);
    }
    public function store(Request $request)
    {
        return $this->rStore($this->m, $request, $this->pk);
    }
    public function search(Request $request)
    {
        $columns = ['rec_id','group_id', 'container_id'];

        $model = Container_grp
            ::orderBy('container_id', 'asc');

        return ModelTreatment::getAsyncData($model, $request, $columns, 'crm', 'container_group', 'rec_id', 'ASC');
    }
  
    public function itemsList($mode, $search = '')
    {
        $container_group = DB::table('container_group')
            ->select('group_id', DB::raw("CONCAT(grp_name, ' ', domin) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'group_id') {
            $container_group = $container_group->where('group_id', $search);
        } else if ($mode == 'phrase') {
            $container_group = $container_group->where(DB::raw("CONCAT(grp_name, ' ', domin)"), 'like', '%' . $search . '%');
        }
        return $container_group->take(100)->get();
    }
}
