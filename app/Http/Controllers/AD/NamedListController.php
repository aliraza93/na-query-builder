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
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store', 'multipleAdd']]);
        $this->middleware('role:update', ['only' => ['update']]);
        $this->middleware('role:delete', ['only' => ['destroy', 'multipleDelete']]);
    }

    private $m = NamedList::class;
    private $pk = 'list_id';

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
    public function update($prefix, Request $request, NamedList $model)
    {
        //  return $this->rUpdate($this->m, $model, $request->all(), $this->pk);
        return $this->sUpdate($this->m, $model, $request->all(), $this->pk, $prefix);
    }
    public function store(Request $request)
    {
        return $this->rStore($this->m, $request, $this->pk);
    }
    public function destroy($prefix, NamedList $model)
    {

        $model->destroy($prefix);

        return ['status' => 0];
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
    }catch (\Exception $e) {

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
}
