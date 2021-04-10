<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\Crm\Grp_conainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GrpcontainerController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store']]);
        $this->middleware('role:update', ['only' => ['update', 'multipleUpdate']]);
        $this->middleware('role:delete', ['only' => ['destroy']]);
    }

    private $m = Grp_conainer::class;
    private $pk = 'rec_id';

    public function index()
    {
        return Grp_conainer
            ::orderBy('group_id', 'asc')
            ->get();
    }

    public function show($id)
    {
        //usergrp
        $Users_grpInfo = Grp_conainer
            ::where('container_id', $id)

            ->first();
        return $Users_grpInfo;
    }
    public function destroy($prefix, Grp_conainer $model)
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

        $model = Grp_conainer
            ::orderBy('group_id', 'asc');

        return ModelTreatment::getAsyncData($model, $request, $columns, 'crm', 'container_group', 'rec_id', 'ASC');
    }
  
    public function itemsList($mode, $search = '')
    {
        $container_group = DB::table('container_group')
            ->select('container_id', DB::raw("CONCAT(user_name, ' ', domin) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'container_id') {
            $container_group = $container_group->where('container_id', $search);
        } else if ($mode == 'phrase') {
            $container_group = $container_group->where(DB::raw("CONCAT(user_name, ' ', domin)"), 'like', '%' . $search . '%');
        }
        return $container_group->take(100)->get();
    }
}
