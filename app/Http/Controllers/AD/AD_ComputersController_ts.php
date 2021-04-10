<?php

namespace App\Http\Controllers\AD;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\AD\AD_Computer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AD_ComputersController_ts extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store', 'multipleAdd']]);
        $this->middleware('role:update', ['only' => ['store']]);
        $this->middleware('role:delete', ['only' => ['destroy','multipleDelete']]);
    }

    private $m = AD_Computer::class;
    private $pk = 'ts_id';

    public function index()
    {
        return AD_Computer
            ::orderBy('common_name', 'asc')
            ->with('memberof', 'memberof.grpdetail')
            ->get();
    }

    public function show($id)
    {
        $AD_cominfo = AD_Computer
            ::where('ts_id', $id)
            ->with('memberof', 'memberof.grpdetail')
            ->with('computercontainers', 'computercontainers.parentcontainer')
            ->with('observed_ip', 'observed_ip.computerinfo')
            ->with('computerinous', 'computerinous.parentous')

            ->first();
        
        if ($AD_cominfo == null) {
            return [];
        }
        $AD_cominfo['policycomputer'] = ModelTreatment::getpolicycomputer($id);

            
        return $AD_cominfo;
    }
    public function multipleDelete(Request $request)
    {
    //    return ['status' => 0];
        
        $model = $this->m;
        $pk = $this->pk;

        $ids = $request->get('ts_id');

        $model::whereIn($pk, $ids)->delete();

        return ['status' => 0];
    }
    public function multipleAdd(Request $request, AD_Computer $model)
    {
        $items = $request->get('items');

        $model::insert($items);
        return ['status' => 0];
    }
    public function update($prefix, Request $request, AD_Computer $model)
    {
        //  return $this->rUpdate($this->m, $model, $request->all(), $this->pk);
        return $this->sUpdate($this->m, $model, $request->all(), $this->pk, $prefix);
    }
    public function store(Request $request)
    {
        return $this->rStore($this->m, $request, $this->pk);
    }
    public function destroy($prefix, AD_Computer $model)
    {

        $model->destroy($prefix);

        return ['status' => 0];
    }
  
    public function search(Request $request)
    {
        $columns = [
            'ts_id',
            'object_guid',
            'common_name',
            'obj_dist_name',
            'when_created',
            'when_changed',
            'sam_account_name',
            'last_logon',
            'operating_system',
            'operating_system_service_pack',
            'operating_system_version'


        ];
        if ($request->has('detail') && $request->get('detail') == "all") {
            $model = AD_Computer
                ::orderBy('common_name', 'asc')->with('memberof', 'memberof.grpdetail');
        } else {
            $model = AD_Computer
                ::select($columns);
        }

       

        return ModelTreatment::getAsyncData($model, $request, $columns, 'ad', 'ad_computer', 'common_name', 'ASC');
    }
    public function itemsList($mode, $search = '')
    {
        $AD_Computer = DB::table('ad_computer')
            ->select('ts_id', DB::raw("CONCAT(common_name, ' ', ts_id) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'ts_id') {
            $AD_Computer = $AD_Computer->where('ts_id', $search);
        } else if ($mode == 'phrase') {
            $AD_Computer = $AD_Computer->where(DB::raw("CONCAT(common_name, ' ', ts_id)"), 'like', '%' . $search . '%');
        }
        return $AD_Computer->take(100)->get();
    }
}
