<?php

namespace App\Http\Controllers\AD;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RuleoptionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store']]);
        $this->middleware('role:update', ['only' => ['store']]);
        $this->middleware('role:delete', ['only' => ['destroy']]);
    }

  
    public function itemsList( $search = '')
    {
        $Rules = DB::table('rule')
            ->select('rule_id', DB::raw("CONCAT(rule_name, ' ', rule_id) as phrase"))
            ->orderBy("phrase", 'asc');

        $Rules = $Rules->where('rule_id', $search);
        return $Rules->take(100)->get();
    }
}
