<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\Crm\Sub_net;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubnetController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store']]);
        $this->middleware('role:update', ['only' => ['update', 'multipleUpdate']]);
        $this->middleware('role:delete', ['only' => ['destroy']]);
    }

    private $m = Sub_net::class;
    private $pk = 'ip_id';

    public function index()
    {
        return Sub_net
            ::orderBy('ip_addr', 'asc')
            ->with('subnetcontainre')
          //  ->with('containergrp')
            ->get();
    }

    public function show($id)
    {
        //usergrp
        $Sub_netInfo = Sub_net
            ::where('ip_id', $id)
            ->with('subnetcontainre', 'subnetcontainre.containername')
            ->with('policysubnet', 'policysubnet.policyname')
           // ->with('containergrp', 'containergrp.containername')
            ->first();
        return $Sub_netInfo;
    }

    public function search(Request $request)
    {
        $columns = ['ip_id', 'ip_addr', 'descr'];

        $model = Sub_net
            ::orderBy('ip_addr', 'asc');

        return ModelTreatment::getAsyncData($model, $request, $columns, 'crm', 'ips_subnet', 'ip_addr', 'ASC');
    }
    public function itemsList($mode, $search = '')
    {
        $ips_subnet = DB::table('ips_subnet')
            ->select('ip_id', DB::raw("CONCAT(ip_addr) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'ip_id') {
            $ips_subnet = $ips_subnet->where('ip_id', $search);
        } else if ($mode == 'phrase') {
            $ips_subnet = $ips_subnet->where(DB::raw("CONCAT(ip_addr)"), 'like', '%' . $search . '%');
        }
        return $ips_subnet->take(100)->get();
    }
}
