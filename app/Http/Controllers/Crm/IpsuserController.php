<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\Crm\Ip_user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IpsuserController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store']]);
        $this->middleware('role:update', ['only' => ['update', 'multipleUpdate']]);
        $this->middleware('role:delete', ['only' => ['destroy']]);
    }

    private $m = Ip_user::class;
    private $pk = 'rec_id';

    public function index()
    {
        return Ip_user
            ::orderBy('user_id', 'asc')
            ->get();
    }

    public function show($id)
    {
        //usergrp
        $Users_containerInfo = Ip_user
            ::where('ip_addr', $id)
            ->first();
        return $Users_containerInfo;
    }
    public function destroy($prefix, Ip_user $model)
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
        $columns = ['rec_id','ip_addr', 'user_id'];

        $model = Ip_user
            ::orderBy('user_id', 'asc');

        return ModelTreatment::getAsyncData($model, $request, $columns, 'crm', 'ip_mapping', 'rec_id', 'ASC');
    }
  
    public function itemsList($mode, $search = '')
    {
        $ip_mapping = DB::table('ip_mapping')
            ->select('ip_addr', DB::raw("CONCAT(grp_name, ' ', domin) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'ip_addr') {
            $ip_mapping = $ip_mapping->where('ip_addr', $search);
        } else if ($mode == 'phrase') {
            $ip_mapping = $ip_mapping->where(DB::raw("CONCAT(grp_name, ' ', domin)"), 'like', '%' . $search . '%');
        }
        return $ip_mapping->take(100)->get();
    }
}
