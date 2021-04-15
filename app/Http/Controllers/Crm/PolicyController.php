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
        // $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        // $this->middleware('role:insert', ['only' => ['store']]);
        // $this->middleware('role:update', ['only' => ['update', 'multipleUpdate']]);
        // $this->middleware('role:delete', ['only' => ['destroy']]);
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $old_value = Policies::orderBy('when_created', 'desc')->first();
        $request->validate([
            'policy_name' => 'required',
            'block_pages' => 'required',
        ]);
        try{
            
            $policy = new Policies;
            $policy->policy_name = $request->policy_name;
            $policy->block_page_id = $request->block_pages['code'];
            $policy->priority = $old_value->priority + 1;
            $policy->when_created = now();
            $policy->save();

            return response()->json(['status'=>'success','message'=>'Policy Added Successfully !']);
        }
        catch(\Exception $e)
        {
         
            return response()->json(['status'=>'error','message'=>'Something Went Wrong !']);

        }
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function policies()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/policy/policies/index', ['pageConfigs' => $pageConfigs]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function settings()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/policy/settings/index', ['pageConfigs' => $pageConfigs]);
    }

    // Policies List
    public function policies_list(Request $request)
    {
        $policy_name        = $request->policy_name;
        $priority           = $request->priority;
        $policies           = DB::table('policy')->orderBy('when_created','desc');
        if($policy_name != ''){
            $policies->where('policy_name','LIKE','%'.$policy_name.'%');
        }
        if($priority != ''){
            $policies->where('priority','LIKE','%'.$priority.'%');
        }
        $policies = $policies->paginate(10);
        return $policies;
    }
}
