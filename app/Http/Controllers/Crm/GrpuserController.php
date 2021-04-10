<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\Crm\Grp_user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GrpuserController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store']]);
        $this->middleware('role:update', ['only' => ['update', 'multipleUpdate']]);
        $this->middleware('role:delete', ['only' => ['destroy']]);
    }

    private $m = Grp_user::class;
    private $pk = 'rec_id';

    public function index()
    {
        return Grp_user
            ::orderBy('group_id', 'asc')
            ->get();
    }

    public function show($id)
    {
        //usergrp
        $Users_grpInfo = Grp_user
            ::where('user_id', $id)
            ->first();
        return $Users_grpInfo;
    }
    public function destroy($prefix, Grp_user $model)
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
        $columns = ['rec_id','group_id', 'user_id'];

        $model = Grp_user
            ::orderBy('group_id', 'asc');

        return ModelTreatment::getAsyncData($model, $request, $columns, 'crm', 'user_group', 'rec_id', 'ASC');
    }
  
    public function itemsList($mode, $search = '')
    {
        $user_group = DB::table('user_group')
            ->select('user_id', DB::raw("CONCAT(user_name, ' ', domin) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'user_id') {
            $user_group = $user_group->where('user_id', $search);
        } else if ($mode == 'phrase') {
            $user_group = $user_group->where(DB::raw("CONCAT(user_name, ' ', domin)"), 'like', '%' . $search . '%');
        }
        return $user_group->take(100)->get();
    }
}
