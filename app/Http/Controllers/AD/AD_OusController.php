<?php

namespace App\Http\Controllers\AD;
use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\AD\AD_Ous;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AD_OusController extends Controller
{
    public function __construct()
    {
        // $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        // $this->middleware('role:insert', ['only' => ['store','mstore', 'multipleAdd']]);
        // $this->middleware('role:update', ['only' => ['update']]);
        // $this->middleware('role:delete', ['only' => ['destroy']]);
    }

    private $m = AD_Ous::class;
    private $pk = 'object_guid';

    public function index()
    {
        return AD_Ous::orderBy('common_name', 'asc')->get();
    }

    public function show($id)
    {
        $AD_OusInfo = AD_Ous
            ::where('object_guid', $id)
            ->first();
        if ($AD_OusInfo == null) {
            return [];
        }   
        return $AD_OusInfo;
    }
    public function multipleAdd(Request $request, AD_Ous $model)
    {
        $items = $request->get('items');

        // $model::insert($items);
        foreach ($items as $item) {
            $pk = ['object_guid'   => $item['object_guid']];
            $model::updateOrCreate($pk, $item);
        }
        return ['status' => 0];
    }
    public function update($prefix, Request $request, AD_Ous $model)
    {
        //  return $this->rUpdate($this->m, $model, $request->all(), $this->pk);
        return $this->sUpdate($this->m, $model, $request->all(), $this->pk, $prefix);
    }
    public function store(Request $request)
    {
        return $this->Storeorupdate($this->m, $request, $this->pk);
    }
    public function destroy($prefix, AD_Ous $model)
    {

        $model->destroy($prefix);

        return ['status' => 0];
    }
  
    public function search(Request $request)
    {
        $columns = [
            'object_guid',
            'common_name',
            'obj_dist_name',
            'when_created',
             'when_changed'
        ];

        $model = AD_Ous
            ::orderBy('common_name', 'asc');

        return ModelTreatment::getAsyncData($model, $request, $columns, 'ad', 'ad_org_unit', 'common_name', 'ASC');
    }
    public function itemsList($mode, $search = '')
    {
        $AD_Ous = DB::table('ad_org_unit')
            ->select('object_guid', DB::raw("CONCAT(common_name, ' ', object_guid) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'object_guid') {
            $AD_Ous = $AD_Ous->where('object_guid', $search);
        } else if ($mode == 'phrase') {
            $AD_Ous = $AD_Ous->where(DB::raw("CONCAT(common_name, ' ', object_guid)"), 'like', '%' . $search . '%');
        }
        return $AD_Ous->take(100)->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function organizational_units()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/ad-data/organizational-units/index', ['pageConfigs' => $pageConfigs]);
    }

    // Organizational Units List
    public function ou_list(Request $request)
    {
        $common_name        = $request->common_name;
        $obj_dist_name      = $request->obj_dist_name;
        $ous                = DB::table('ad_org_unit')->orderBy('when_created','desc');
        if($common_name != ''){
            $ous->where('common_name','LIKE','%'.$common_name.'%');
        }
        if($obj_dist_name != ''){
            $ous->where('obj_dist_name','LIKE','%'.$obj_dist_name.'%');
        }
        $ous = $ous->paginate(10);
        return $ous;
    }

}
