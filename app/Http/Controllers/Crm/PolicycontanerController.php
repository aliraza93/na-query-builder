<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\Crm\Policy_container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PolicycontanerController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store']]);
        $this->middleware('role:update', ['only' => ['update', 'multipleUpdate']]);
        $this->middleware('role:delete', ['only' => ['destroy']]);
    }

    private $m = Policy_container::class;
    private $pk = 'rec_id';

    public function index()
    {
        return Policy_container
            ::orderBy('priority', 'asc')
            ->get();
    }

    public function show($id)
    {
        //usergrp
        $Policy_rulesInfo = Policy_container
            ::where('rec_id', $id)
            ->first();
        return $Policy_rulesInfo;
    }
    public function destroy($prefix, Policy_container $model)
    {

        $model->destroy($prefix);
       
     
        return ['status' => 0]; 
     //   return $this->rDestroy($model);
    }
    public function update($prefix, Request $request, Policy_container $model)
    {
        //  return $this->rUpdate($this->m, $model, $request->all(), $this->pk);
        return $this->sUpdate($this->m, $model, $request->all() , $this->pk, $prefix);
    }
    public function store(Request $request)
    {
        return $this->rStore($this->m, $request, $this->pk);
    }
    public function search(Request $request)
    {
        $columns = ['rec_id','policy_id', 'container_id', 'priority'];

        $model = Policy_container
            ::orderBy('priority', 'asc');

        return ModelTreatment::getAsyncData($model, $request, $columns, 'crm', 'policy_container', 'rec_id', 'ASC');
    }
  
    public function itemsList($mode, $search = '')
    {
        $policy_container = DB::table('policy_container')
            ->select('policy_id', DB::raw("CONCAT(policy_name, ' ', domin) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'policy_id') {
            $policy_container = $policy_container->where('policy_id', $search);
        } else if ($mode == 'phrase') {
            $policy_container = $policy_container->where(DB::raw("CONCAT(policy_name, ' ', domin)"), 'like', '%' . $search . '%');
        }
        return $policy_container->take(100)->get();
    }
}
