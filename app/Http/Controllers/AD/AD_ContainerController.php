<?php

namespace App\Http\Controllers\AD;
use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\AD\AD_Container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AD_ContainerController extends Controller
{
    public function __construct()
    {
        // $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        // $this->middleware('role:insert', ['only' => ['store', 'multipleAdd']]);
        // $this->middleware('role:update', ['only' => ['update']]);
        // $this->middleware('role:delete', ['only' => ['destroy', 'multipleDelete']]);
    }

    private $m = AD_Container::class;
    private $pk = 'ts_id';

    public function index()
    {
        return AD_Container
            ::orderBy('common_name', 'asc')
            ->get();
    }

    public function show($id)
    {
        $AD_containerInfo = AD_Container
            ::where('ts_id', $id)
            ->with('computersincontainer', 'computersincontainer.computerdetail')
            ->with('usersincontainer', 'usersincontainer.userdetail')
            ->with('groupsincontainer', 'groupsincontainer.grpdetail')
            ->with('containerincontainer', 'containerincontainer.containerdetail')
            ->with('subnetincontainer', 'subnetincontainer.subnetdetail')
            ->with('ousincontainer', 'ousincontainer.ousdetail')

                ->first();
        if ($AD_containerInfo == null) {
            return [];
        }
        $AD_containerInfo['policycontainer'] = ModelTreatment::getpolicycontainer($id);

        return $AD_containerInfo;
    }
    public function update($prefix, Request $request, AD_Container $model)
    {
        //  return $this->rUpdate($this->m, $model, $request->all(), $this->pk);
        return $this->sUpdate($this->m, $model, $request->all(), $this->pk, $prefix);
    }
    public function store(Request $request)
    {
        $request->validate([
            'common_name' => 'required',
        ]);
        try {

            $this->rStore($this->m, $request, $this->pk);
            return response()->json(['status' => 'success', 'message' => 'Container Added Successfully !']);
        } catch (\Exception $e) {

            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    public function destroy($prefix, AD_Container $model)
    {

        $model->destroy($prefix);

        return ['status' => 0];
    }
    public function multipleAdd(Request $request, AD_Container $model)
    {
        $items = $request->get('items');

        $model::insert($items);
        return ['status' => 0];
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
    public function search(Request $request)
    {
        $columns = [
            'ts_id',
            'common_name',
            'container_uid',
        ];
        try {  

        if ($request->has('detail') && $request->get('detail') == "all") {
      
            $model = AD_Container
            ::orderBy('common_name', 'asc')
                    ->with('computersincontainer', 'computersincontainer.computerdetail')
                    ->with('usersincontainer', 'usersincontainer.userdetail')
                    ->with('groupsincontainer', 'groupsincontainer.grpdetail')
                    ->with('containerincontainer', 'containerincontainer.containerdetail');
       }else{
            $model = AD_Container
                ::orderBy('common_name', 'asc');

       }
        return ModelTreatment::getAsyncData($model, $request, $columns, 'ad', 'container', 'common_name', 'ASC');
    }catch (\Exception $e) {

            return $e->getMessage();

            // something went wrong
        }
    }
    public function itemsList($mode, $search = '')
    {
        $AD_Container = DB::table('container')
            ->select('ts_id', DB::raw("CONCAT(common_name, ' ', container_uid) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'ts_id') {
            $AD_Container = $AD_Container->where('ts_id', $search);
        } else if ($mode == 'phrase') {
            $AD_Container = $AD_Container->where(DB::raw("CONCAT(common_name, ' ', container_uid)"), 'like', '%' . $search . '%');
        }
        return $AD_Container->take(100)->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function containers()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/ad-data/containers/index', ['pageConfigs' => $pageConfigs]);
    }

    // Containers List
    public function containers_list(Request $request)
    {
        $common_name        = $request->common_name;
        $containers          = DB::table('container')->orderBy('when_created','desc');
        if($common_name != ''){
            $containers->where('common_name','LIKE','%'.$common_name.'%');
        }
        $containers = $containers->paginate(10);
        return $containers;
    }

}
