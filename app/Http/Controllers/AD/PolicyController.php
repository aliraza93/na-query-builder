<?php

namespace App\Http\Controllers\AD;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\AD\Policies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PolicyController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store']]);
        $this->middleware('role:update', ['only' => ['update', 'multipleUpdate']]);
        $this->middleware('role:delete', ['only' => ['destroy']]);
    }

    private $m = Policies::class;
    private $pk = 'policy_id';

    public function index()
    {
        return Policies
            ::orderBy('priority', 'asc')
           // ->with('policyrule')
            //->with('policyuser')
          //  ->with('containergrp')
            ->get();
    }

    public function show($id)
    {
        //usergrp
        $PoliciesInfo = Policies
            ::where('policy_id', $id)
            ->with('policyrule', 'policyrule.rulename')
            ->with('policyuser', 'policyuser.username')
            ->with('policygrp', 'policygrp.grpname')
            ->with('policycontainer', 'policycontainer.contname')
            ->with('policysubnet', 'policysubnet.subname')
            ->with('policycomputer', 'policycomputer.computername')
            ->with('policyous', 'policyous.ousname')

            ->first();
        
        return $PoliciesInfo;
    }
    public function update($prefix, Request $request, Policies $model)
    {
        //  return $this->rUpdate($this->m, $model, $request->all(), $this->pk);
        $id = $request->get('policy_id');
        $priority = $request->get('priority') ;
            if ($request->get('action') === 'up' ) {
               $checknewpro = $priority -1;
               if ($checknewpro > 0) {
                 $checkpolicy =  Policies::where('priority','=', $checknewpro)->first();
                   if($checkpolicy !== null){
                    $negtive_priority = -1 * $checknewpro;
                    Policies::where('priority', '=', $checknewpro)
                    ->update(array('priority' => $negtive_priority));

                    Policies::where('priority', '=', $priority)
                    ->update(array('priority' => $checknewpro));
                    $postive_pro = -1 * $negtive_priority;
                    Policies::where('priority', '=', $negtive_priority)
                    ->update(array('priority' => $priority));
                    return ['status' => 0];

                   }else{
                    Policies::where('priority', '=', $priority)
                        ->update(array('priority' => $checknewpro));
                    return ['status' => 0];

                   }
               }else{
                 return ['status' => 0];

               }
               
            }

        if ($request->get('action') === 'down') {
            $checknewpro = $priority + 1;
            $max = Policies::max('priority');

            if ($priority < $max) {
                $checkpolicy =  Policies::where('priority', '=', $checknewpro)->first();
                if ($checkpolicy !== null) {
                    $negtive_priority = -1 * $checknewpro;
                    Policies::where('priority', '=', $checknewpro)
                    ->update(array('priority' => $negtive_priority));

                    Policies::where('priority', '=', $priority)
                    ->update(array('priority' => $checknewpro));
                    $postive_pro = -1 * $negtive_priority;
                    Policies::where('priority', '=', $negtive_priority)
                        ->update(array('priority' => $priority));
                    return ['status' => 0];
                } else {
                    Policies::where('priority', '=', $priority)
                        ->update(array('priority' => $checknewpro));
                    return ['status' => 0];
                }
            } else {
                return ['status' => 0];
            }
        }
        
        return $this->sUpdate($this->m, $model, $request->all(), $this->pk, $prefix);
    }
    public function store(Request $request)
    {
        if($request->get('priority') == null){
           $maxp = $this->m::max('priority') +1;

            $request->request->add(['priority' => $maxp]);

 
        }

        return $this->rStore($this->m, $request, $this->pk);
    }
    public function destroy($prefix, Policies $model)
    {

        $model->destroy($prefix);

        return ['status' => 0];
    }

    public function search(Request $request)
    {
        $columns = ['policy_id', 'priority', 'policy_name', 'block_page_id'];

        $model = Policies
            ::with('blockpage');

        return ModelTreatment::getAsyncData($model, $request, $columns, 'AD', 'policy', 'priority', 'ASC');
    }
    public function itemsList($mode, $search = '')
    {
        $policy = DB::table('policy')
            ->select('policy_id', DB::raw("CONCAT(policy_name, ' ', priority) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'policy_id') {
            $policy = $policy->where('policy_id', $search);
        } else if ($mode == 'phrase') {
            $policy = $policy->where(DB::raw("CONCAT(policy_name, ' ', priority)"), 'like', '%' . $search . '%');
        }
        return $policy->take(100)->get();
    }
}
