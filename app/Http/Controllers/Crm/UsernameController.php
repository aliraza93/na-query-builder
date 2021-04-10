<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\Crm\User_name;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsernameController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store']]);
        $this->middleware('role:update', ['only' => ['update', 'multipleUpdate']]);
        $this->middleware('role:delete', ['only' => ['destroy']]);
    }

    private $m = User_name::class;
    private $pk = 'user_id';

    public function index()
    {
        return User_name
            ::orderBy('u_name', 'asc')
            ->get();
    }

    public function show($id)
    {
        $User_nameInfo = User_name
            ::where('user_id', $id)
            ->with('groupsname', 'groupsname.grpname')
            ->with('containername', 'containername.contname')
            ->with('policyuser', 'policyuser.policyname')
            ->with('ipsofuser')
            ->first();
        return $User_nameInfo;
    }

    public function search(Request $request)
    {
        $columns = ['user_id', 'domin', 'u_name', 'descr'];

        $model = User_name
            ::orderBy('u_name', 'asc');

        return ModelTreatment::getAsyncData($model, $request, $columns, 'crm', 'user_name', 'u_name', 'ASC');
    }
    public function itemsList($mode, $search = '')
    {
        $user_name = DB::table('user_name')
            ->select('user_id', DB::raw("CONCAT(u_name, ' ', domin) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'user_id') {
            $user_name = $user_name->where('user_id', $search);
        } else if ($mode == 'phrase') {
            $user_name = $user_name->where(DB::raw("CONCAT(u_name, ' ', domin)"), 'like', '%' . $search . '%');
        }
        return $user_name->take(100)->get();
    }
}
