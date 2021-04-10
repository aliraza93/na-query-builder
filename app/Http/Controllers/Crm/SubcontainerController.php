<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\Crm\Subnet_container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubcontainerController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store']]);
        $this->middleware('role:update', ['only' => ['update', 'multipleUpdate']]);
        $this->middleware('role:delete', ['only' => ['destroy']]);
    }

    private $m = Subnet_container::class;
    private $pk = 'rec_id';

    public function index()
    {
        return Subnet_container
            ::orderBy('container_id', 'asc')
            ->get();
    }

    public function show($id)
    {
        //usergrp
        $Subnet_containerInfo = Subnet_container
            ::where('ip_id', $id)
            ->first();
        return $Subnet_containerInfo;
    }
    public function destroy($prefix, Subnet_container $model)
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

        $model = Subnet_container
            ::orderBy('container_id', 'asc');

        return ModelTreatment::getAsyncData($model, $request, $columns, 'crm', 'ips_subnet', 'rec_id', 'ASC');
    }
  
    public function itemsList($mode, $search = '')
    {
        $ips_subnet = DB::table('ips_subnet')
            ->select('ip_id', DB::raw("CONCAT(grp_name, ' ', domin) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'ip_id') {
            $ips_subnet = $ips_subnet->where('ip_id', $search);
        } else if ($mode == 'phrase') {
            $ips_subnet = $ips_subnet->where(DB::raw("CONCAT(grp_name, ' ', domin)"), 'like', '%' . $search . '%');
        }
        return $ips_subnet->take(100)->get();
    }
}
