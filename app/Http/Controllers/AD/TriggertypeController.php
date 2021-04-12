<?php

namespace App\Http\Controllers\AD;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\AD\Trigger_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TriggertypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store']]);
        $this->middleware('role:update', ['only' => ['update', 'multipleUpdate']]);
        $this->middleware('role:delete', ['only' => ['destroy']]);
    }

    private $m = Trigger_type::class;
    private $pk = 'trigger_code';

    public function index()
    {
        $db = DB::connection('pgsql');
        $statment = 'select rbt.trigger_code, rbt.trigger_label, rbt.seq_no, rbt.input_code,
         rbi.has_choice_list_flag,rbt.trigger_code_parent
         from rule_builder_trigger rbt
          inner join rule_builder_input rbi
          on rbt.input_code = rbi.input_code
           where rbt.has_sub_level_flag = false
          order by 3';
        $collection = $db->select($statment);
        return  $collection;
    }

    public function show($id)
    {
        $User_nameInfo = Trigger_type
            ::where('trigger_code', $id)
            ->first();
        return $User_nameInfo;
    }

    public function search(Request $request)
    {
        $columns = ['trigger_code', 'trigger_label'];

        $model = Trigger_type
            ::orderBy('trigger_label', 'asc');

        return ModelTreatment::getAsyncData($model, $request, $columns, 'AD', 'rule_builder_trigger', 'trigger_label', 'ASC');
    }
    public function itemsList($mode, $search = '')
    {
        $user_name = DB::table('user_name')
            ->select('trigger_code', DB::raw("CONCAT(trigger_label) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'trigger_code') {
            $user_name = $user_name->where('trigger_code', $search);
        } else if ($mode == 'phrase') {
            $user_name = $user_name->where(DB::raw("CONCAT(trigger_label, ' ', domin)"), 'like', '%' . $search . '%');
        }
        return $user_name->take(100)->get();
    }
}
