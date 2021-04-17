<?php

namespace App\Http\Controllers\AD;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\AD\Policies;
use App\Models\AD\Policy_rules;
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
            ::orderBy('priority', 'asc')
            // ->with('policyrule')
            //->with('policyuser')
            //  ->with('containergrp')
            ->get();
    }

    public function get_rules()
    {
        return Policy_rules::orderBy('rule_id', 'asc')->get();
    }

    public function show($id)
    {
        //usergrp
        $PoliciesInfo = Policies::where('policy_id', $id)
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
    public function update(Request $request, Policies $policy)
    {
        $request->validate([
            'policy_name' => 'required',
            'block_pages' => 'required'
        ]);
        try {

            $policy->policy_name = $request->policy_name;
            $policy->block_page_id = $request->block_pages['code'];
            $policy->when_changed = now();
            $policy->update();

            return response()->json(['status' => 'success', 'message' => 'Policy Updated Successfully !']);
        } catch (\Exception $e) {

            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function change_priority(Policies $policy, $action)
    {
        $id = $policy->policy_id;
        $priority = $policy->priority;
        if ($action === 'up') {
            $checknewpro = $priority - 1;
            if ($checknewpro > 0) {
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

        if ($action === 'down') {
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
    }

    public function edit(Policies $policy)
    {
        return response()->json([
            'policy'    => $policy,
            'blockpage' => $policy->blockpage
        ]);
    }

    public function store(Request $request)
    {
        $old_value = Policies::orderBy('when_created', 'desc')->first();
        $request->validate([
            'policy_name' => 'required',
            'block_pages' => 'required',
        ]);
        try {

            $policy = new Policies;
            $policy->policy_name = $request->policy_name;
            $policy->block_page_id = $request->block_pages['code'];
            $policy->priority = empty($old_value) ? 1 : $old_value->priority + 1;
            $policy->when_created = now();
            $policy->save();

            return response()->json(['status' => 'success', 'message' => 'Policy Added Successfully !']);
        } catch (\Exception $e) {

            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function destroy(Policies $policy)
    {
        try {
            $policy->delete();
            return response()->json(['status' => 'success', 'message' => 'Policy Deleted Successfully !']);
        } catch (\Exception $e) {

            return response()->json(['status' => 'error', 'message' => 'Something Went Wrong !']);
        }
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
    public function policy_details(Policies $policy)
    {
        $pageConfigs = ['pageHeader' => false];
        return view('content.policy.policies.policy-details', ['pageConfigs' => $pageConfigs], compact('policy'));
    }

    // Reports List Page
    public function reports()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/policy/reports/index', ['pageConfigs' => $pageConfigs]);
    }

    // Policies List
    public function policies_list(Request $request)
    {
        $policy_name        = $request->policy_name;
        $priority           = $request->priority;
        $policies           = Policies::with('blockpage')->orderBy('priority');
        if ($policy_name != '') {
            $policies->where('policy_name', 'LIKE', '%' . $policy_name . '%');
        }
        if ($priority != '') {
            $policies->where('priority', 'LIKE', '%' . $priority . '%');
        }
        $policies = $policies->paginate(10);
        return $policies;
    }
}
