<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\Crm\Container_subnet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContainersubnetController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store']]);
        $this->middleware('role:update', ['only' => ['update', 'multipleUpdate']]);
        $this->middleware('role:delete', ['only' => ['destroy']]);
    }

    private $m = Container_subnet::class;
    private $pk = 'rec_id';

    public function index()
    {
        return Container_subnet
            ::orderBy('ip_id', 'asc')
            ->get();
    }

    public function show($id)
    {
        //usergrp
        $Users_grpInfo = Container_subnet
            ::where('container_id', $id)

            ->first();
        return $Users_grpInfo;
    }
    public function destroy($prefix, Container_subnet $model)
    {

        $model->destroy($prefix);
       
     
        return ['status' => 0]; 
     //   return $this->rDestroy($model);
    }
    public function store(Request $request)
    {
        return $this->rStore($this->m, $request, $this->pk);
    }
    public function search(Request $request)
    {
        $columns = ['rec_id','ip_id', 'container_id'];

        $model = Container_subnet
            ::orderBy('ip_id', 'asc');

        return ModelTreatment::getAsyncData($model, $request, $columns, 'crm', 'container_ips', 'rec_id', 'ASC');
    }
  
    public function itemsList($mode, $search = '')
    {
        $container_ips = DB::table('container_ips')
            ->select('container_id', DB::raw("CONCAT(user_name, ' ', domin) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'container_id') {
            $container_ips = $container_ips->where('container_id', $search);
        } else if ($mode == 'phrase') {
            $container_ips = $container_ips->where(DB::raw("CONCAT(user_name, ' ', domin)"), 'like', '%' . $search . '%');
        }
        return $container_ips->take(100)->get();
    }
}
