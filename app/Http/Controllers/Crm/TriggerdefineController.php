<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\Crm\Trigger_define;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TriggerdefineController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store']]);
        $this->middleware('role:update', ['only' => ['update', 'multipleUpdate']]);
        $this->middleware('role:delete', ['only' => ['destroy']]);
    }

    private $m = Trigger_define::class;
    private $pk = 'tr_id';

    public function index()
    {
        return Trigger_define
            ::orderBy('tr_name', 'asc')
            ->get();
    }

    public function show($id)
    {
        $User_nameInfo = Trigger_define
            ::where('tr_id', $id)
            ->first();
        return $User_nameInfo;
    }

    public function search(Request $request)
    {
        $columns = ['tr_id', 'tr_name'];

        $model = Trigger_define
            ::orderBy('tr_name', 'asc');

        return ModelTreatment::getAsyncData($model, $request, $columns, 'crm', 'trigger_define', 'tr_name', 'ASC');
    }
    public function store(Request $request)
    {
        return $this->rStore($this->m, $request, $this->pk);
    }
    public function itemsList($mode, $search = '')
    {
        $trigger_define = DB::table('trigger_define')
            ->select('tr_id', DB::raw("CONCAT(tr_name) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'tr_id') {
            $trigger_define = $trigger_define->where('tr_id', $search);
        } else if ($mode == 'phrase') {
            $trigger_define = $trigger_define->where(DB::raw("CONCAT(tr_name, ' ', domin)"), 'like', '%' . $search . '%');
        }
        return $trigger_define->take(100)->get();
    }
}
