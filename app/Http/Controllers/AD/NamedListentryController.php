<?php

namespace App\Http\Controllers\AD;
use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\AD\NamedListentry;
use App\Models\AD\NamedList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NamedListentryController extends Controller
{
    public function __construct()
    {
        // $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        // $this->middleware('role:insert', ['only' => ['store', 'multipleAdd']]);
        // $this->middleware('role:update', ['only' => ['update']]);
        // $this->middleware('role:delete', ['only' => ['destroy', 'multipleDelete']]);
    }

    private $m = NamedListentry::class;
    private $pk = 'list_entry_id';

    public function index()
    {
        return NamedListentry::orderBy('match_string', 'asc')->get();
    }

    public function show($id)
    {
        $NamedListInfo = NamedListentry
            ::where('list_entry_id', $id)
                ->first();
        if ($NamedListInfo == null) {
            return [];
        }

        return $NamedListInfo;
    }
    public function update($prefix, Request $request, NamedListentry $model)
    {
        //  return $this->rUpdate($this->m, $model, $request->all(), $this->pk);
        return $this->sUpdate($this->m, $model, $request->all(), $this->pk, $prefix);
    }
    public function store(Request $request, NamedList $url)
    {
        $request->validate([
            'match_string' => ['required'],
            'operator_code' => ['required']
        ]);
        try {
            $request->request->set('list_id', $url->list_id);
            $this->rStore($this->m, $request, $this->pk);
            return response()->json(['status'=>'success','message'=>'List Entry Added Successfully !']);
        }
        catch(\Exception $e)
        {
            return response()->json(['status'=>'error','message'=>'Something Went Wrong !']);
        }
    }
    public function destroy($prefix, NamedListentry $model)
    {

        $model->destroy($prefix);

        return ['status' => 0];
    }
    public function multipleAdd(Request $request, NamedListentry $model)
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

        $ids = $request->get('list_entry_id');

        $model::whereIn($pk, $ids)->delete();

        return ['status' => 0];
    }
    public function search(Request $request)
    {
        $columns = [
            'list_id',
            'list_entry_id',
            'match_string',
            'operator_code'
        ];
        try {  
        
            $model = NamedListentry::select($columns) ;
       
        return ModelTreatment::getAsyncData($model, $request, $columns, 'ad', 'named_list', 'match_string', 'ASC');
    }catch (\Exception $e) {

            return $e->getMessage();

            // something went wrong
        }
    }
    public function itemsList($mode, $search = '')
    {
        $NamedListentry = DB::table('named_list')
            ->select('list_entry_id', DB::raw("CONCAT(match_string, ' ', container_uid) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'list_entry_id') {
            $NamedListentry = $NamedListentry->where('list_entry_id', $search);
        } else if ($mode == 'phrase') {
            $NamedListentry = $NamedListentry->where(DB::raw("CONCAT(match_string, ' ', container_uid)"), 'like', '%' . $search . '%');
        }
        return $NamedListentry->take(100)->get();
    }

    // Block pages List
    public function named_entry_list(Request $request, NamedList $url)
    {
        // $title              = $request->title;
        // $default_page_flag  = $request->default_page;
        $namedList          = DB::table('named_list_entry')->where('list_id', $url->list_id)->orderBy('when_created','desc');
        // if($title != ''){
        //     $namedList->where('title','LIKE','%'.$title.'%');
        // }
        // if($default_page_flag != ''){
        //     $namedList->where('default_page_flag','LIKE','%'.$default_page_flag.'%');
        // }
        $namedList = $namedList->paginate(10);
        return $namedList;
    }
}
