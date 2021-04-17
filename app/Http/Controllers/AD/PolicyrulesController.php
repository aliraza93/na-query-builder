<?php

namespace App\Http\Controllers\AD;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\AD\Policy_rules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PolicyrulesController extends Controller
{
    public function __construct()
    {
        // $this->middleware('role:read', ['only' => ['index', 'show', 'search', 'custget']]);
        // $this->middleware('role:insert', ['only' => ['store']]);
        // $this->middleware('role:update', ['only' => ['update', 'multipleUpdate']]);
        // $this->middleware('role:delete', ['only' => ['destroy', 'custdestroy']]);
    }

    private $m = Policy_rules::class;
    private $pk = 'rule_id';

    public function index()
    {
        return Policy_rules::orderBy('rule_id', 'asc')->get();
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
    public function custget(Request $request, Policy_rules $model)
    {
        $Policy_rulesInfo = Policy_rules
        ::where('rule_id', '=', $request->get('rule_id'))
        ->where('policy_id', '=', $request->get('policy_id'))
            ->first();
        return $Policy_rulesInfo;
    }
    public function custdestroy(Request $request, Policy_rules $model)
    {
        // return ['status' => 0,"1"=>$request->get('policy_id')];

        $model->where('rule_id', '=', $request->get('rule_id'))
        ->where('policy_id', '=', $request->get('policy_id'))->delete();
        return ['status' => 0];
    }
    public function update($prefix, Request $request, Policy_rules $model)
    {

        //  return $this->rUpdate($this->m, $model, $request->all(), $this->pk);
        $policyid = $request->get('policy_id');

        $priority = $request->get('priority');
        if ($request->get('action') === 'up') {
            $checknewpro = $priority - 1;
            if ($checknewpro > 0) {
                $checkpolicy =  Policy_rules::where('priority', '=', $checknewpro)
                ->where('policy_id','=', $policyid)
                ->first();
                if ($checkpolicy !== null) {
                    $negtive_priority = -1 * $checknewpro;
                    Policy_rules::where('priority', '=', $checknewpro)
                        ->where('policy_id', '=', $policyid)
                        ->update(array('priority' => $negtive_priority));

                    Policy_rules::where('priority', '=', $priority)
                         ->where('policy_id', '=', $policyid)
                        ->update(array('priority' => $checknewpro));
                    Policy_rules::where('priority', '=', $negtive_priority)
                        ->where('policy_id', '=', $policyid)
                        ->update(array('priority' => $priority));
                    return ['status' => 0];
                } else {
                    Policy_rules::where('priority', '=', $priority)
                    ->where('policy_id', '=', $policyid)
                        ->update(array('priority' => $checknewpro));
                    return ['status' => 0];
                }
            } else {
                return ['status' => 0];
            }
        }

        if ($request->get('action') === 'down') {
            $checknewpro = $priority + 1;
            $max = Policy_rules::where('policy_id', '=', $policyid)->max('priority');

            if ($priority < $max) {
                $checkpolicy =  Policy_rules::where('priority', '=', $checknewpro)
                    ->where('policy_id', '=', $policyid)
                ->first();
                if ($checkpolicy !== null) {
                    $negtive_priority = -1 * $checknewpro;
                    Policy_rules::where('priority', '=', $checknewpro)->where('policy_id', '=', $policyid)
                        ->update(array('priority' => $negtive_priority));

                    Policy_rules::where('priority', '=', $priority)->where('policy_id', '=', $policyid)
                        ->update(array('priority' => $checknewpro));
                    $postive_pro = -1 * $negtive_priority;
                    Policy_rules::where('priority', '=', $negtive_priority)->where('policy_id', '=', $policyid)
                        ->update(array('priority' => $priority));
                    return ['status' => 0];
                } else {
                    Policy_rules::where('priority', '=', $priority)->where('policy_id', '=', $policyid)
                        ->update(array('priority' => $checknewpro));
                    return ['status' => 0];
                }
            } else {
                return ['status' => 0];
            }
        }
        return $this->compsetUpdate(
        $this->m, $model, $request->all() ,
         ['policy_id','rule_id'],
         $prefix);
        
    }
    public function store(Request $request)
    {
       
        if ($request->get('priority') == null) {
            $maxp = $this->m::where('policy_id', $request->get('policy_id'))->max('priority') + 1;

            $request->request->add(['priority' => $maxp]);
        }
        return $this->rStore($this->m, $request, $this->pk);
    }
    public function search(Request $request)
    {
        $columns = ['rule_id','policy_id', 'name'];

        $model = Policy_rules
            ::orderBy('priority', 'asc');

        return ModelTreatment::getAsyncData($model, $request, $columns, 'AD', 'policy_rule', 'priority', 'ASC');
    }
  
    public function itemsList($mode, $search = '')
    {
        $policy_rules = DB::table('policy_rule')
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
