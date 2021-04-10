<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\Crm\Container;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContainerController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store']]);
        $this->middleware('role:update', ['only' => ['update', 'multipleUpdate']]);
        $this->middleware('role:delete', ['only' => ['destroy']]);
    }

    private $m = Container::class;
    private $pk = 'container_id';

    public function index()
    {
        return Container
            ::orderBy('container_name', 'asc')
            ->get();
    }

    public function show($id)
    {
        $ContainerInfo = Container
            ::where('container_id', $id)
            ->with('subcontainer', 'subcontainer.subname')
            ->with('usercontainer', 'usercontainer.username')
            ->with('ipsname', 'ipsname.ipadrr')
            ->with('containergrp', 'containergrp.grpname')
            ->with('policycontainer', 'policycontainer.policyname')
            ->first();
        return $ContainerInfo;
    }

    public function search(Request $request)
    {
        $columns = ['container_id', 'container_priority', 'container_name', 'descr'];

        $model = Container
            ::orderBy('container_name', 'asc');

        return ModelTreatment::getAsyncData($model, $request, $columns, 'crm', 'Container', 'container_name', 'ASC');
    }
    public function itemsList($mode, $search = '')
    {
        $Container = DB::table('Container')
            ->select('container_id', DB::raw("CONCAT(container_name, ' ', container_priority) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'container_id') {
            $Container = $Container->where('container_id', $search);
        } else if ($mode == 'phrase') {
            $Container = $Container->where(DB::raw("CONCAT(container_name, ' ', container_priority)"), 'like', '%' . $search . '%');
        }
        return $Container->take(100)->get();
    }
}
