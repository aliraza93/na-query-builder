<?php

namespace App\Http\Controllers\AD;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\AD\Trigger_define;
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
    private $pk = 'input_code';

    public function index()
    {
        /*   $collection = Trigger_define
            ::orderBy('choice_label', 'asc')
            ->get();
        $grouped = $collection->groupBy('input_code');
        return  $grouped->toArray();*/
        $db = DB::connection('pgsql');
        $collection = $db->select('select *,choice_label As text,choice_code AS value from choice_list_all()');
        return  $collection;


    }

    public function show($id)
    {
        $User_nameInfo = Trigger_define
            ::where('input_code', $id)
            ->first();
        return $User_nameInfo;
    }

    public function search(Request $request)
    {
        $db = DB::connection('pgsql');
        $collection = $db->select('select * from choice_list_all()');
        $grouped = $collection->groupBy('input_code');




        return  $grouped->toArray();
    }
    public function store(Request $request)
    {
        return $this->rStore($this->m, $request, $this->pk);
    }
    public function itemsList($mode, $search = '')
    {
        $trigger_define = DB::table('trigger_define')
            ->select('input_code', DB::raw("CONCAT(choice_label) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'input_code') {
            $trigger_define = $trigger_define->where('input_code', $search);
        } else if ($mode == 'phrase') {
            $trigger_define = $trigger_define->where(DB::raw("CONCAT(choice_label, ' ', domin)"), 'like', '%' . $search . '%');
        }
        return $trigger_define->take(100)->get();
    }
}
