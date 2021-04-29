<?php

namespace App\Http\Controllers\AD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TriggerOperatorsController extends Controller
{
    public function __construct()
    {
        // $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        // $this->middleware('role:insert', ['only' => ['store']]);
        // $this->middleware('role:update', ['only' => ['update', 'multipleUpdate']]);
        // $this->middleware('role:delete', ['only' => ['destroy']]);
    }



    // public function index()
    // {
    //     $db = DB::connection('pgsql');
    //     $collection = $db->select('select *,operator_label As text,operator_code AS value from operator_list_all()');
    //     return  $collection;

    // }

    public function index() {
        $operators = DB::table('rule_builder_operator')->orderBy('operator_label')->get();
        return $operators;
    }

    public function getDistinctOperators() {
        $operators = DB::table('rule_builder_operator')->distinct('operator_label')->orderBy('operator_label')->get();
        return $operators;
    }

    public function show($id)
    {
        $db = DB::connection('pgsql');
        $statement = 'SELECT input_code, seq_no, operator_label, operator_code, complement_field_count
	    FROM public.rule_builder_operator';      
        $policy = $db->select($statement, [$id, $id, $id]);
        //return $User_nameInfo;
    }

   
}
