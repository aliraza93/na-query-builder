<?php

namespace App\Http\Controllers\AD;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\AD\AD_Computer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AD_ComputersController extends Controller
{
    public function __construct()
    {
        // $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        // $this->middleware('role:insert', ['only' => ['store', 'multipleAdd']]);
        // $this->middleware('role:update', ['only' => ['store']]);
        // $this->middleware('role:delete', ['only' => ['destroy','multipleDelete']]);
    }
    private $m = AD_Computer::class;
    private $pk = 'object_guid';

    //Computer List Page
    public function computers() {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/ad-data/computers/index', ['pageConfigs' => $pageConfigs]);
    }

    public function index()
    {
        return AD_Computer::orderBy('common_name', 'asc')->with('memberof', 'memberof.grpdetail')->get();
    }

    public function show($id)
    {
        $AD_cominfo = AD_Computer
            ::where('object_guid', $id)
            ->with('memberof', 'memberof.grpdetail')
            ->first();
        
        if ($AD_cominfo == null) {
            return [];
        } 
            
        return $AD_cominfo;
    }
    public function multipleDelete(Request $request)
    {
    //    return ['status' => 0];
        
        $model = $this->m;
        $pk = $this->pk;

        $ids = $request->get('object_guid');

        $model::whereIn($pk, $ids)->delete();

        return ['status' => 0];
    }
    public function multipleAdd(Request $request, AD_Computer $model)
    {
        $items = $request->get('items');

        // $model::insert($items);
        foreach ($items as $item) {
            $pk = ['object_guid'   => $item['object_guid']];
            $model::updateOrCreate($pk, $item);
        }
        return ['status' => 0];
    }
    public function update($prefix, Request $request, AD_Computer $model)
    {
        //  return $this->rUpdate($this->m, $model, $request->all(), $this->pk);
        return $this->sUpdate($this->m, $model, $request->all(), $this->pk, $prefix);
    }
    public function store(Request $request)
    {
        return $this->Storeorupdate($this->m, $request, $this->pk);
    }
    public function destroy($prefix, AD_Computer $model)
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
                ::orderBy('common_name', 'asc');
        }

       

        return ModelTreatment::getAsyncData($model, $request, $columns, 'ad', 'ad_computer', 'common_name', 'ASC');
    }
    public function itemsList($mode, $search = '')
    {
        $AD_Computer = DB::table('ad_computer')
            ->select('object_guid', DB::raw("CONCAT(common_name, ' ', object_guid) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'object_guid') {
            $AD_Computer = $AD_Computer->where('object_guid', $search);
        } else if ($mode == 'phrase') {
            $AD_Computer = $AD_Computer->where(DB::raw("CONCAT(common_name, ' ', object_guid)"), 'like', '%' . $search . '%');
        }
        return $AD_Computer->take(100)->get();
    }

    // Computer List
    public function computer_list(Request $request)
    {
        $common_name        = $request->common_name;
        $sam_account_name   = $request->sam_account_name;
        $operating_system   = $request->operating_system;
        $computers          = DB::table('ad_computer')->orderBy('when_created','desc');
        if($common_name != ''){
            $computers->where('common_name','LIKE','%'.$common_name.'%');
        }
        if($sam_account_name != ''){
            $computers->where('sam_account_name','LIKE','%'.$sam_account_name.'%');
        }
        if($operating_system != ''){
            $computers->where('operating_system','LIKE','%'.$operating_system.'%');
        }
        $computers = $computers->paginate(10);
        return $computers;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showComputer(User $user)
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/ad-data/computers/show-ad-data-computer', ['pageConfigs' => $pageConfigs], compact('user'));
    }
}
