<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\Crm\Policies;
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
            ::orderBy('policy_name', 'asc')
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
            ->with('policyrule')
            ->with('policyuser', 'policyuser.username')
            ->with('policygrp', 'policygrp.grpname')
            ->with('policycontainer', 'policycontainer.contname')
            ->with('policysubnet', 'policysubnet.subname')
            ->first();
        
        return $PoliciesInfo;
    }

    public function search(Request $request)
    {
        $columns = ['policy_id', 'priority', 'policy_name', 'descr'];

        $model = Policies
            ::orderBy('policy_name', 'asc');

        return ModelTreatment::getAsyncData($model, $request, $columns, 'crm', 'policy', 'policy_name', 'ASC');
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
