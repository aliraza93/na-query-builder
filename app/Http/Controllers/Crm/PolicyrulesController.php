<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\Crm\Policy_rules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PolicyrulesController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store']]);
        $this->middleware('role:update', ['only' => ['update', 'multipleUpdate']]);
        $this->middleware('role:delete', ['only' => ['destroy']]);
    }

    private $m = Policy_rules::class;
    private $pk = 'rule_id';

    public function index()
    {
        return Policy_rules
            ::orderBy('rule_id', 'asc')
            ->get();
    }

    public function show($id)
    {
        //usergrp
        $Policy_rulesInfo = Policy_rules
            ::where('rule_id', $id)
            ->first();
        return $Policy_rulesInfo;
    }
    public function destroy($prefix, Policy_rules $model)
    {

        $model->destroy($prefix);
       
     
        return ['status' => 0]; 
     //   return $this->rDestroy($model);
    }
    public function update($prefix, Request $request, Policy_rules $model)
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
        $columns = ['rule_id','policy_id', 'r_action', 'descr', 'priority', 'r_condition'];

        $model = Policy_rules
            ::orderBy('priority', 'asc');

        return ModelTreatment::getAsyncData($model, $request, $columns, 'crm', 'policy_rules', 'rule_id', 'ASC');
    }
  
    public function itemsList($mode, $search = '')
    {
        $policy_rules = DB::table('policy_rules')
            ->select('policy_id', DB::raw("CONCAT(policy_name, ' ', domin) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'policy_id') {
            $policy_rules = $policy_rules->where('policy_id', $search);
        } else if ($mode == 'phrase') {
            $policy_rules = $policy_rules->where(DB::raw("CONCAT(policy_name, ' ', domin)"), 'like', '%' . $search . '%');
        }
        return $policy_rules->take(100)->get();
    }
}
