<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\UserType;
use App\Http\Controllers\Controller;

class UserTypesController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index']]);
    }

    public function index()
    {
        return UserType::orderBy('id', 'asc')->get();
    }
    
}
