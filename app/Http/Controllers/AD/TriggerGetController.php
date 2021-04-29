<?php

namespace App\Http\Controllers\AD;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\AD\Trigger_define;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TriggerGetController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store']]);
        $this->middleware('role:update', ['only' => ['update', 'multipleUpdate']]);
        $this->middleware('role:delete', ['only' => ['destroy']]);
    }

   
    public function index()
    {
        $collection = Trigger_define
            ::orderBy('choice_label', 'asc')
            ->get();
        $grouped = $collection->groupBy('input_code');

        return  $grouped->toArray();


    }

    public function show($id)
    {
        $User_nameInfo = Trigger_define
            ::where('input_code', $id)
            ->first();
        return $User_nameInfo;
    }

    
}
