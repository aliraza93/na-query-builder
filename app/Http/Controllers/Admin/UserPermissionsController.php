<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\UserPermission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserPermissionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index']]);
        $this->middleware('role:insert', ['only' => ['store', 'multipleAdd']]);
        $this->middleware('role:delete', ['only' => ['destroy', 'multipleDelete']]);
    }

    private $m = UserPermission::class;
    private $pk = 'id';

    public function index()
    {
        return UserPermission
            ::orderBy('id', 'asc')
            ->with('user')
            ->with('permission')
            ->get();
    }
    
    public function store(Request $request)
    {
        return $this->rStore($this->m, $request, $this->pk);
    }

    public function destroy($prefix, UserPermission $model)
    {
        $model->destroy($prefix);

        return ['status' => 0];
    }

    public function multipleAdd(Request $request)
    {
        return $this->rMultipleAdd($this->m, $request);
    }

    public function multipleDelete(Request $request)
    {
        return $this->rMultipleDelete($this->m, $request, $this->pk);
    }
}
