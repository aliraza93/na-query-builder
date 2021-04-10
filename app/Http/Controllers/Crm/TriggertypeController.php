<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\Crm\Trigger_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TriggertypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store']]);
        $this->middleware('role:update', ['only' => ['update', 'multipleUpdate']]);
        $this->middleware('role:delete', ['only' => ['destroy']]);
    }

    private $m = Trigger_type::class;
    private $pk = 'tr_type';

    public function index()
    {
        return Trigger_type
            ::orderBy('type_name', 'asc')
            ->get();
    }

    public function show($id)
    {
        $User_nameInfo = Trigger_type
            ::where('tr_type', $id)
            ->first();
        return $User_nameInfo;
    }

    public function search(Request $request)
    {
        $columns = ['tr_type', 'type_name'];

        $model = Trigger_type
            ::orderBy('type_name', 'asc');

        return ModelTreatment::getAsyncData($model, $request, $columns, 'crm', 'trigger_type', 'type_name', 'ASC');
    }
    public function itemsList($mode, $search = '')
    {
        $user_name = DB::table('user_name')
            ->select('tr_type', DB::raw("CONCAT(type_name) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'tr_type') {
            $user_name = $user_name->where('tr_type', $search);
        } else if ($mode == 'phrase') {
            $user_name = $user_name->where(DB::raw("CONCAT(type_name, ' ', domin)"), 'like', '%' . $search . '%');
        }
        return $user_name->take(100)->get();
    }
}
