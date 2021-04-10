<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\Crm\Container_user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContaineruserController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store']]);
        $this->middleware('role:update', ['only' => ['update', 'multipleUpdate']]);
        $this->middleware('role:delete', ['only' => ['destroy']]);
    }

    private $m = Container_user::class;
    private $pk = 'rec_id';

    public function index()
    {
        return Container_user
            ::orderBy('user_id', 'asc')
            ->get();
    }

    public function show($id)
    {
        //usergrp
        $Users_containerInfo = Container_user
            ::where('container_id', $id)
            ->first();
        return $Users_containerInfo;
    }
    public function destroy($prefix, Container_user $model)
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
        $columns = ['rec_id','container_id', 'user_id'];

        $model = Container_user
            ::orderBy('user_id', 'asc');

        return ModelTreatment::getAsyncData($model, $request, $columns, 'crm', 'container_users', 'rec_id', 'ASC');
    }
  
    public function itemsList($mode, $search = '')
    {
        $user_group = DB::table('user_group')
            ->select('container_id', DB::raw("CONCAT(grp_name, ' ', domin) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'container_id') {
            $user_group = $user_group->where('container_id', $search);
        } else if ($mode == 'phrase') {
            $user_group = $user_group->where(DB::raw("CONCAT(grp_name, ' ', domin)"), 'like', '%' . $search . '%');
        }
        return $user_group->take(100)->get();
    }
}
