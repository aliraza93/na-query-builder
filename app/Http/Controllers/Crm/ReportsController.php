<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
//use App\Models\Libraries\ReportTreatment;
use App\Models\Crm\Reports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use timgws\QueryBuilderParser;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store']]);
        $this->middleware('role:update', ['only' => ['update', 'multipleUpdate']]);
        $this->middleware('role:delete', ['only' => ['destroy']]);
    }

    private $m = Reports::class;
    private $pk = 'user_id';

    public function index()
    {
        return Reports
            ::orderBy('u_name', 'asc')
            ->get();
    }


    public function show($id)
    {
        $User_nameInfo = Reports
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
        /*
        $model = Reports
            ::orderBy('u_name', 'asc');

        return ReportTreatment::getAsyncData($model, $request, $columns, 'crm', 'user_name', 'u_name', 'ASC');
        */
        $json = '{
    "condition": "AND",
    "rules": [
        {
            "id": "user_id",
            "field": "user_id",
            "type": "string",
            "input": "text",
            "operator": "equal",
            "value": "1110"
        }

    ]
}';


        //  $childrens = $request;  ->limit($rowsPerPage)
        $rowsPerPage = $request->get('rowsPerPage');
        $param = $request->get('param');
        $paramfilter = $param[0];
        $table = DB::table('user_name');
        $sortBy = $request->get('sortBy');
        $sortDesc = $request->get('sortDesc');

        $table = DB::table('user_name');
        if ($sortBy != null && $sortDesc != null) {
            for ($i = 0; $i < count($sortBy); $i++) {
                $direction = $sortDesc[$i] == 'true' ? 'desc' : 'asc';
                //$model->orderBy($sortBy[$i], $direction);
                $table->orderBy($sortBy[$i], $direction);
            }
        }
        $qbp = new QueryBuilderParser(
            // provide here a list of allowable rows from the query builder.
            // NOTE: if a row is listed here, you will be able to create limits on that row from QBP.
            $columns
        );
        //json_encode($request->all())
 
        //$req =  stringify($request->all());  $json
        
        $query = $qbp->parse(json_encode($paramfilter), $table);

        
         
        $rows = $query->paginate($rowsPerPage);
        
        //  $model->paginate($rowsPerPage);
        //  $casts = ['data' => $rows, 'current_page' => 1];
        
        return $rows;
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
