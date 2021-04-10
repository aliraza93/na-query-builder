<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\Crm\Sub_container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubcontanerController  extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store']]);
        $this->middleware('role:update', ['only' => ['update', 'multipleUpdate']]);
        $this->middleware('role:delete', ['only' => ['destroy']]);
    }

    private $m = Sub_container::class;
    private $pk = 'rec_id';

    public function index()
    {
        return Sub_container
            ::orderBy('container_id', 'asc')
            ->get();
    }

    public function show($id)
    {
        //usergrp
        $Sub_containerInfo = Sub_container
            ::where('sub_container_id', $id)
            ->first();
        return $Sub_containerInfo;
    }
    public function destroy($prefix, Sub_container $model)
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
        $columns = ['rec_id','sub_container_id', 'container_id'];

        $model = Sub_container
            ::orderBy('container_id', 'asc');

        return ModelTreatment::getAsyncData($model, $request, $columns, 'crm', 'sub_container', 'rec_id', 'ASC');
    }
  
    public function itemsList($mode, $search = '')
    {
        $Sub_container = DB::table('container_sub')
            ->select('sub_container_id', DB::raw("CONCAT(grp_name, ' ', domin) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'sub_container_id') {
            $Sub_container = $Sub_container->where('sub_container_id', $search);
        } else if ($mode == 'phrase') {
            $Sub_container = $Sub_container->where(DB::raw("CONCAT(grp_name, ' ', domin)"), 'like', '%' . $search . '%');
        }
        return $Sub_container->take(100)->get();
    }
}
