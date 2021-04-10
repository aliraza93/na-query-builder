<?php

namespace App\Http\Controllers\AD;
use App\Http\Controllers\Controller;
use App\Models\AD\PolicySetting;
use Illuminate\Http\Request;

class PolicySettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index']]);
        $this->middleware('role:update', ['only' => ['update']]);
    }

    private $m = PolicySetting::class;
    private $pk = 'unique_row';

    public function index()
    {
      return PolicySetting
        ::first();
    }

    public function update($prefix, Request $request, PolicySetting $model)
    {
        return $this->sUpdate($this->m, $model, $request->all(), $this->pk, $prefix);
    }

}
