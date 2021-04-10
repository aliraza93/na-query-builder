<?php

namespace App\Http\Controllers\AD;
use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\AD\AD_Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AD_Users_ts_idController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store', 'multipleAdd']]);
        $this->middleware('role:update', ['only' => ['update']]);
        $this->middleware('role:delete', ['only' => ['destroy', 'multipleDelete']]);
    }

    private $m = AD_Users::class;
    private $pk = 'ts_id';

    public function index()
    {
        return AD_Users
            ::orderBy('common_name', 'asc')
            ->with('memberof', 'memberof.grpdetail')
             ->get();
    }

    public function show($id)
    {

        $AD_UsersInfo = AD_Users
        ::where('ts_id', $id)
        ->with('memberof', 'memberof.grpdetail')
        ->with('userincontainers', 'userincontainers.parentcontainer')
        ->with('observed_ip', 'observed_ip.userinfo')
        ->with('userinous', 'userinous.parentous')
         ->first();

        if ($AD_UsersInfo == null) {
            return [];
        } 

        $AD_UsersInfo['policyuser'] = ModelTreatment::getpolicyuser($id);

        
        return $AD_UsersInfo;
    }
    public function update($prefix, Request $request, AD_Users $model)
    {
        //  return $this->rUpdate($this->m, $model, $request->all(), $this->pk);
        return $this->sUpdate($this->m, $model, $request->all(), $this->pk, $prefix);
    }
    public function store(Request $request)
    {
        return $this->rStore($this->m, $request, $this->pk);
    }
    public function destroy($prefix, AD_Users $model)
    {
        // return ['status' => 0];

        $model->destroy($prefix);

        return ['status' => 0];
    }
    
   
    public function multipleAdd(Request $request, AD_Users $model)
    {
        $items = $request->get('items');

        $model::insert($items);
        return ['status' => 0];
    }

    public function multipleDelete(Request $request)
    {
        //    return ['status' => 0];

        $model = $this->m;
        $pk = $this->pk;

        $ids = $request->get('ts_id');

        $model::whereIn($pk, $ids)->delete();

        return ['status' => 0];
    }
    public function search(Request $request)
    {
        $columns = [
            'ts_id',
            'common_name',
            'surname',
            'given_name',
            'sam_account_name',
            'physical_delivery_office_name',
            'telephone_number',
            'email_addresses',
            'department',
            'obj_dist_name',
            'last_logon',
            'logon_count',
            'user_principal_name',
            'is_enabled',
            'when_created',
            'when_changed',


        ];
        try {  
        if ($request->has('detail') && $request->get('detail') == "all") {
      
            $model = AD_Users
            ::orderBy('common_name', 'asc')
            ->with('memberof', 'memberof.grpdetail');
       }else{
            $model = AD_Users::select($columns);
               

       }
        return ModelTreatment::getAsyncData($model, $request, $columns, 'ad', 'ad_user', 'common_name', 'ASC');
    }catch (\Exception $e) {

            return $e->getMessage();

            // something went wrong
        }
    }
    public function itemsList($mode, $search = '')
    {
        $AD_Users = DB::table('ad_user')
            ->select('ts_id', DB::raw("CONCAT(common_name, ' ', ts_id) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'ts_id') {
            $AD_Users = $AD_Users->where('ts_id', $search);
        } else if ($mode == 'phrase') {
            $AD_Users = $AD_Users->where(DB::raw("CONCAT(common_name, ' ', ts_id)"), 'like', '%' . $search . '%');
        }
        return $AD_Users->take(100)->get();
    }
}
