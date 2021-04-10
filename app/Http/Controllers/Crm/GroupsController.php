<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\Crm\Group_name;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupsController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store']]);
        $this->middleware('role:update', ['only' => ['update', 'multipleUpdate']]);
        $this->middleware('role:delete', ['only' => ['destroy']]);
    }

    private $m = Group_name::class;
    private $pk = 'group_id';

    public function index()
    {
        return Group_name
            ::orderBy('grp_name', 'asc')
            ->with('usergrp')
            ->with('containergrp')
            ->get();
    }

    public function show($id)
    {
        //usergrp
        $Group_nameInfo = Group_name
            ::where('group_id', $id)
            ->with('usergrp', 'usergrp.username')
            ->with('containergrp', 'containergrp.containername')
            ->with('policygrp', 'policygrp.policyname')
            ->first();
        return $Group_nameInfo;
    }

    public function search(Request $request)
    {
        $columns = ['group_id', 'domin', 'grp_name', 'descr'];

        $model = Group_name
            ::orderBy('grp_name', 'asc');

        return ModelTreatment::getAsyncData($model, $request, $columns, 'crm', 'group_name', 'grp_name', 'ASC');
    }
    public function itemsList($mode, $search = '')
    {
        $group_name = DB::table('group_name')
            ->select('group_id', DB::raw("CONCAT(grp_name, ' ', domin) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'group_id') {
            $group_name = $group_name->where('group_id', $search);
        } else if ($mode == 'phrase') {
            $group_name = $group_name->where(DB::raw("CONCAT(grp_name, ' ', domin)"), 'like', '%' . $search . '%');
        }
        return $group_name->take(100)->get();
    }
}
