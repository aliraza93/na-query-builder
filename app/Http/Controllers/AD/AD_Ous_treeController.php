<?php

namespace App\Http\Controllers\AD;
use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\AD\AD_Ous_ts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AD_Ous_treeController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store','mstore']]);
        $this->middleware('role:update', ['only' => ['update']]);
        $this->middleware('role:delete', ['only' => ['destroy']]);
    }

    private $m = AD_Ous_ts::class;
    private $pk = 'ts_id';

    public function index()
    {
        return AD_Ous_ts
            ::orderBy('common_name', 'asc')
            ->get();
    }

    public function show($id)
    {
        $AD_OusInfo = AD_Ous_ts
        ::where('ts_id', $id)
        ->with('ousinous', 'ousinous.ousdetail')
         ->first();
        if ($AD_OusInfo == null) {
            return [];
        }
        $AD_OusInfo['policycontainer'] = ModelTreatment::getpolicyouse($id);

        return $AD_OusInfo;
    }
    public function update($prefix, Request $request, AD_Ous_ts $model)
    {
        //  return $this->rUpdate($this->m, $model, $request->all(), $this->pk);
        return $this->sUpdate($this->m, $model, $request->all(), $this->pk, $prefix);
    }
    public function store(Request $request)
    {
        return $this->rStore($this->m, $request, $this->pk);
    }
    public function destroy($prefix, AD_Ous_ts $model)
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
             'when_changed'
        ];

        $model = AD_Ous_ts
            ::select($columns);

        return ModelTreatment::getAsyncData($model, $request, $columns, 'ad', 'ad_org_unit', 'common_name', 'ASC');
    }
    public function itemsList($mode, $search = '')
    {
        $AD_Ous_ts = DB::table('ad_org_unit')
            ->select('ts_id', DB::raw("CONCAT(common_name, ' ', ts_id) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'ts_id') {
            $AD_Ous_ts = $AD_Ous_ts->where('ts_id', $search);
        } else if ($mode == 'phrase') {
            $AD_Ous_ts = $AD_Ous_ts->where(DB::raw("CONCAT(common_name, ' ', ts_id)"), 'like', '%' . $search . '%');
        }
        return $AD_Ous_ts->take(100)->get();
    }
}
