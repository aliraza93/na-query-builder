<?php

namespace App\Http\Controllers\AD;
use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\AD\NamedList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NamedListController extends Controller
{
    public function __construct()
    {
        // $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        // $this->middleware('role:insert', ['only' => ['store', 'multipleAdd']]);
        // $this->middleware('role:update', ['only' => ['update']]);
        // $this->middleware('role:delete', ['only' => ['destroy', 'multipleDelete']]);
    }

    private $m = NamedList::class;
    private $pk = 'list_id';

    // URL List Page
    public function url_list()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/policy/url-list/index', ['pageConfigs' => $pageConfigs]);
    }

    public function index()
    {
        return NamedList
            ::orderBy('list_title', 'asc')
            ->get();
    }

    public function show($id)
    {
        $NamedListInfo = NamedList
            ::where('list_id', $id)
           ->with('members')
          
                ->first();
        if ($NamedListInfo == null) {
            return [];
        }

        return $NamedListInfo;
    }

    public function edit(NamedList $url) {
        return $url;
    }

    public function update(Request $request, NamedList $url)
    {
        $request->validate([
            'list_title' => 'required',
        ]);
        try{ 
            $url->list_title = $request->list_title;
            $url->when_changed = now();
            $url->update();
            return response()->json(['status'=>'success','message'=>'URL List Updated Successfully !']);
        }
        catch(\Exception $e)
        {
         
            return response()->json(['status'=>'error','message'=>'Something Went Wrong !']);

        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'list_title' => 'required',
        ]);
        try{
            $this->rStore($this->m, $request, $this->pk);

            return response()->json(['status'=>'success','message'=>'List Added Successfully !']);
        }
        catch(\Exception $e)
        {
         
            return response()->json(['status'=>'error','message'=>'Something Went Wrong !']);

        }
    }

    public function destroy(NamedList $url)
    {
        try{
            $url->delete();
            return response()->json(['status'=>'success','message'=>'URL List Deleted Successfully !']);
        }
        catch(\Exception $e)
        {
         
            return response()->json(['status'=>'error','message'=>'Something Went Wrong !']);

        }
    }

    public function multipleAdd(Request $request, NamedList $model)
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

        $ids = $request->get('list_id');

        $model::whereIn($pk, $ids)->delete();

        return ['status' => 0];
    }

    public function search(Request $request)
    {
        $columns = [
            'list_id',
            'list_title',
        ];
        try {  
        
            $model = NamedList
                ::orderBy('list_title', 'asc');
       
            return ModelTreatment::getAsyncData($model, $request, $columns, 'ad', 'named_list', 'list_title', 'ASC');
        }
        catch (\Exception $e) {

            return $e->getMessage();

            // something went wrong
        }
    }

    public function itemsList($mode, $search = '')
    {
        $NamedList = DB::table('named_list')
            ->select('list_id', DB::raw("CONCAT(list_title, ' ', container_uid) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'list_id') {
            $NamedList = $NamedList->where('list_id', $search);
        } else if ($mode == 'phrase') {
            $NamedList = $NamedList->where(DB::raw("CONCAT(list_title, ' ', container_uid)"), 'like', '%' . $search . '%');
        }
        return $NamedList->take(100)->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function url_list_details(NamedList $url)
    {
        $pageConfigs = ['pageHeader' => false];
        return view('content.policy.url-list.list-details', ['pageConfigs' => $pageConfigs], compact('url'));
    }

    // Url List
    public function url_name_list(Request $request)
    {
        $list_title     = $request->list_title;
        $url_title      = DB::table('named_list')->orderBy('when_created');
        if ($list_title != '') {
            $url_title->where('list_title', 'LIKE', '%' . $list_title . '%');
        }
        $url_title = $url_title->paginate(10);
        return $url_title;
    }
}
