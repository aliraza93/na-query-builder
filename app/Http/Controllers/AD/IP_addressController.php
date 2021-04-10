<?php

namespace App\Http\Controllers\AD;
use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\AD\IP_address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IP_addressController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store', 'multipleAdd']]);
        $this->middleware('role:update', ['only' => ['update']]);
        $this->middleware('role:delete', ['only' => ['destroy', 'multipleDelete']]);
    }

    private $m = IP_address::class;
    private $pk = 'ts_id';

    public function index()
    {
        return IP_address
            ::orderBy('name', 'asc')
            ->get();
    }

    public function show($id)
    {
        $ip_addressInfo = IP_address
            ::where('ts_id', $id)
            ->with('ipincontainers', 'ipincontainers.parentcontainer')
                ->first();
        if ($ip_addressInfo == null) {
            return [];
        }
        $ip_addressInfo['policysubnet'] = ModelTreatment::getpolicysubnet($id);

        return $ip_addressInfo;
    }
    public function update($prefix, Request $request, IP_address $model)
    {
        //return $this->sUpdate($this->m, $model, $request->all(), $this->pk, $prefix);
        if ($prefix !== null) {
            $points = $request->get('points');
            $rangefrm = $request->get('ip_address_from');
            $rangeto = $request->get('ip_address_to');
            $range = null;
            if ($rangefrm != null) {
                $range = [$rangefrm, $rangeto];
                $ip_address_ranges = $this->to_pg_array($range, 0);
            }else{
                $ip_address_ranges =null;
            }
            if ($points != null) {
                $ip_address_points = $this->to_pg_array($points, 1);
            } else {
                $ip_address_points = null;
            }
            $name = $request->get('name');
            $description = $request->get('description');
            if ($request->get('is_enabled') == null || $request->get('is_enabled') == '') {
                $request->request->set('is_enabled', false);
            }
            $is_enabled =  $request->get('is_enabled');
            
            //$affected = DB::update('update users set votes = 100 where name = ?', ['John']);

            $db = DB::connection('pgsql3');
            $db->update(
         'update  ip_address set 
            ip_address_points =?,
            ip_address_ranges=?,
            name=?,
            is_enabled=?,
            description =? where ts_id = ?',
                [$ip_address_points,  $ip_address_ranges, $name, $is_enabled, $description, $prefix]
            );
            return ['status' => 0];
        }else{
            return ['status' => -1, "message"=>'can not update null id'];
        }

    }
    public function store(Request $request)
    {
        $points = $request->get('points');
        $rangefrm = $request->get('ip_address_from');
        $rangeto = $request->get('ip_address_to');
         $range =null;
        if($rangefrm !=null  ){
            $range = [$rangefrm, $rangeto];
            $ip_address_ranges = $this->to_pg_array($range, 0);
           

        }else{
            $ip_address_ranges  = null;

        }
        if ($points != null) {
            $ip_address_points = $this->to_pg_array($points, 1);
        }else{
            $ip_address_points = null;
        }
        $name = $request->get('name');
        $description = $request->get('description');
        if ($request->get('is_enabled') == null || $request->get('is_enabled') == '') {
            $request->request->set('is_enabled', false);
        }
        $is_enabled=  $request->get('is_enabled');
        
        $db = DB::connection('pgsql3');
        $db->insert(
            'insert into ip_address (ip_address_points,ip_address_ranges,name,is_enabled,description) 
            values (?,?,?,?,?)'
        , [ $ip_address_points,  $ip_address_ranges, $name, $is_enabled, $description]);
        return ['status' => 0] ;
    }
    public function destroy($prefix, IP_address $model)
    {
        $model->destroy($prefix);
        return ['status' => 0];
    }
    public function multipleAdd(Request $request, IP_address $model)
    {
        $items = $request->get('items');

        $model::insert($items);
        return ['status' => 0];
    }

    function to_pg_array($set,$flg)
    {

    if ($flg == 0) {
        settype($set, 'array');
        // can be called with a scalar or array
        $result = array();
        foreach ($set as $t) {
            if (is_array($t)) {
             //   $result[] = $this->to_pg_array($t);
            } else {
                $t = str_replace('"', '\\"', $t);
            
                $result[] = $t;
            }
        }
        return '{"(' . implode(",", $result) . ')"}';
    }
        if ($flg == 1) {
            settype($set, 'array');
            // can be called with a scalar or array
            $result = array();
            foreach ($set as $t) {
                if (is_array($t)) {
                    $result[] = $this->to_pg_array($t, $flg);
                } else {
                    $t = str_replace('"', '\\"', $t);

                    $result[] = $t;
                }
            }
            return '{' . implode(",", $result) . '}';
        }
    // format
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
            'ip_address_points',
            'ip_address_ranges',
            'name',
            'description',
            'is_enabled',
        ];
        try {  
        if ($request->has('detail') && $request->get('detail') == "all") {
      
            $model = IP_address
            ::orderBy('name', 'asc');
       }else{
            $model = IP_address
                    ::select($columns);

       }
        return ModelTreatment::getAsyncData($model, $request, $columns, 'ad', 'ip_address', 'name', 'ASC');
    }catch (\Exception $e) {

            return $e->getMessage();

            // something went wrong
        }
    }
    public function itemsList($mode, $search = '')
    {
        $IP_address = DB::table('ip_address')
            ->select('ts_id', DB::raw("CONCAT(name, ' ', ip_address_points) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'ts_id') {
            $IP_address = $IP_address->where('ts_id', $search);
        } else if ($mode == 'phrase') {
            $IP_address = $IP_address->where(DB::raw("CONCAT(name, ' ', ip_address_points)"), 'like', '%' . $search . '%');
        }
        return $IP_address->take(100)->get();
    }
}
