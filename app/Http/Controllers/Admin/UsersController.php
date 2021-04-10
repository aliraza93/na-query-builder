<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\User;
use App\Models\Admin\Permission;
use App\Models\Libraries\ModelTreatment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search', 'userPermissions']]);
        $this->middleware('role:insert', ['only' => ['store']]);
        $this->middleware('role:update', ['only' => ['update', 'multipleUpdate']]);
    }

    private $m = User::class;
    private $pk = 'id';

    public function index()
    {
        return User
            ::with('userType')
            ->orderBy('id', 'asc')
            ->get();
    }

    public function show($id)
    {
        $userInfo = User
            ::where('id', $id)
            ->first();
        if ($userInfo == null) {
            return [];
        }

        return $userInfo;
    }

    public function update(Request $request, $id)
    {
        $model = User::whereId($id)->first();
        return $this->rUpdate($this->m, $model, $request->all(), $this->pk);
    }

    public function store(Request $request)
    {
        $password = str_random(8);
        $verification_code = str_random(30); //Generate verification code

        $computed = [
            'password' => bcrypt($password),
            'remember_token' => $verification_code
        ];
        return $this->rStore($this->m, $request, $this->pk, $computed);
    }

    public function resetPassword(Request $request, $id)
    {
        $user = User::find($id);
        $password = str_random(8);

        $data = [
            'initial_password' => $password,
            'password' => bcrypt($password)
        ];
        $user->update($data);
        return ['status' => 0, 'id' => $user->id];
    }

    public function userPermissions(Request $request, $id)
    {
        $userPermissions = Permission
            ::orderBy('id', 'asc')
            ->with(
                [
                    'permissionUsers' => function($query) use($id) {
                        $query->where('user_id', $id);
                    },
                ]
            )
            ->get();
        return $userPermissions;
    }
    
    public function multipleUpdate(Request $request)
    {
        return $this->rMultipleUpdate($this->m, $request, $this->pk);
    }

    public function search(Request $request)
    {
        $columns = [
            'id',
            'name',
            'email',
            'initial_password',
            'user_type_id',
            'active'
        ];
        try {  
        
            $model = User
                ::with('userType')
                ->select($columns);
       
            return ModelTreatment::getAsyncData($model, $request, $columns, 'ad', 'login_user', 'name', 'ASC');
        }catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    public function itemsList($mode, $search = '')
    {
        $user = DB::table('login_user')
            ->select('id', DB::raw("name as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'id') {
            $user = $user->where('id', $search);
        } else if ($mode == 'phrase') {
            $user = $user->where(DB::raw("name"), 'like', '%' . $search . '%');
        }
        return $user->take(100)->get();
    }
}
