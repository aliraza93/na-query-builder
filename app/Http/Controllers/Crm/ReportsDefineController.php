<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\Crm\ReportsDefine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsDefineController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store']]);
        $this->middleware('role:update', ['only' => ['update', 'multipleUpdate']]);
        $this->middleware('role:delete', ['only' => ['destroy']]);
    }

    private $m = ReportsDefine::class;
    private $pk = 'id';

    public function index()
    {
        return ReportsDefine
            ::orderBy('report_name', 'asc')
            ->get();
    }
    public function store(Request $request)
    {
        return $this->rStore($this->m, $request, $this->pk);
    }
    public function update($prefix, Request $request, ReportsDefine $model)
    {
        //  return $this->rUpdate($this->m, $model, $request->all(), $this->pk);
        return $this->sUpdate($this->m, $model, $request->all(), $this->pk, $prefix);
    }

    public function show($id)
    {
        $User_nameInfo = ReportsDefine
            ::where('id', $id)
            ->first();
        return $User_nameInfo;
    }

    public function search(Request $request)
    {
        $columns = ['id', 'report_name', 'report_filter'];

        $model = ReportsDefine
            ::orderBy('reports', 'asc');

        return ModelTreatment::getAsyncData($model, $request, $columns, 'crm', 'reports', 'reports', 'ASC');
    }
    public function itemsList($mode, $search = '')
    {
        $reports = DB::table('reports')
            ->select('id', DB::raw("CONCAT(reports, ' ', report_name) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'id') {
            $reports = $reports->where('id', $search);
        } else if ($mode == 'phrase') {
            $reports = $reports->where(DB::raw("CONCAT(reports, ' ', report_name)"), 'like', '%' . $search . '%');
        }
        return $reports->take(100)->get();
    }
}
