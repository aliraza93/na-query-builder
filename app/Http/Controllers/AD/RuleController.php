<?php

namespace App\Http\Controllers\AD;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\AD\Rules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store']]);
        $this->middleware('role:update', ['only' => ['store']]);
        $this->middleware('role:delete', ['only' => ['destroy']]);
    }

    private $m = Rules::class;
    private $pk = 'rule_id';

    public function index()
    {
        return Rules
            ::orderBy('rule_name', 'asc')
              ->get();
    }

    public function show($id)
    {
        $Rules_info = Rules
            ::where('rule_id', $id)
                     ->first();
        
        if ($Rules_info == null) {
            return [];
        } 
            
        return $Rules_info;
    }
    public function multipleDelete(Request $request)
    {
    //    return ['status' => 0];
        
        $model = $this->m;
        $pk = $this->pk;

        $ids = $request->get('rule_id');

        $model::whereIn($pk, $ids)->delete();

        return ['status' => 0];
    }
    public function multipleAdd(Request $request, Rules $model)
    {
        $items = $request->get('items');

        $model::insert($items);
        return ['status' => 0];
    }
    public function update($prefix, Request $request, Rules $model)
    {
        //  return $this->rUpdate($this->m, $model, $request->all(), $this->pk);
        if ($request->get('immediate_flag') == null || $request->get('immediate_flag') == '') {
            $request->request->set('immediate_flag', false);
        }
        return $this->sUpdate($this->m, $model, $request->all(), $this->pk, $prefix);
    }
    public function store(Request $request)
    {
        if ($request->get('immediate_flag') == null || $request->get('immediate_flag') == '') {
            $request->request->set('immediate_flag', false);
        }
        return $this->rStore($this->m, $request, $this->pk);
    }
    public function destroy($prefix, Rules $model)
    {

        $model->destroy($prefix);

        return ['status' => 0];
    }
  
    public function search(Request $request)
    {
        $columns = [
            'rule_id',
            'rule_name',
            'match_action',
            'immediate_flag',


        ];
        if ($request->has('detail') && $request->get('detail') == "all") {
            $model = Rules
                ::orderBy('rule_name', 'asc');
        } else {
            $model = Rules
                ::select($columns);
        }

       

        return ModelTreatment::getAsyncData($model, $request, $columns, 'ad', 'rule', 'rule_name', 'ASC');
    }
    public function itemsList($mode, $search = '')
    {
        $Rules = DB::table('rule')
            ->select('rule_id', DB::raw("CONCAT(rule_name, ' ', rule_id) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'rule_id') {
            $Rules = $Rules->where('rule_id', $search);
        } else if ($mode == 'phrase') {
            $Rules = $Rules->where(DB::raw("CONCAT(rule_name, ' ', rule_id)"), 'like', '%' . $search . '%');
        }
        return $Rules->take(100)->get();
    }
}
