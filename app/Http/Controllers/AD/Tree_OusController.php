<?php

namespace App\Http\Controllers\AD;
use App\Http\Controllers\Controller;
use App\Models\Libraries\TreeTreatment;
use App\Models\AD\AD_Ous_ts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Tree_OusController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store','mstore']]);
        $this->middleware('role:update', ['only' => ['update']]);
        $this->middleware('role:delete', ['only' => ['destroy']]);
    }

    private $m = AD_Ous_ts::class;
    private $pk = 'ts_id';

    public function index()
    {
    $stat = "SELECT ou.ts_id ,ou.common_name as ou_parent,cou.common_name as name,
    tsc.ts_id_child  as id,subtype
	FROM ad_org_unit ou   
left join  traffic_source_contains tsc on ou.ts_id =  tsc.ts_id_parent
left join  ad_org_unit cou on cou.ts_id =  tsc.ts_id_child
left join traffic_source ts on ts.ts_id =  tsc.ts_id_child
where ts.subtype = 'OrgUnit'
 and ou.ts_id not in(select ts_id_child from ad_org_unit cous 
	left join  traffic_source_contains ctsc on cous.ts_id =  ctsc.ts_id_child
	left join traffic_source cts on cts.ts_id =  cous.ts_id
    where cts.subtype = 'OrgUnit' and ts_id_child is not null";
    
    $stat= "SELECT ts_id_parent as parent_menu_item_id , 
    ts_id_child as id, blocked_flag,cou.common_name as text,ou.common_name as path,true as active
	FROM ad_org_unit ou   
	left join traffic_source_contains tsc  on ou.ts_id = tsc.ts_id_parent
    left join ad_org_unit cou  on cou.ts_id = tsc.ts_id_child
    left join traffic_source ts on ts.ts_id =  tsc.ts_id_child
    where ts.subtype = 'OrgUnit'";
        $stat2 = "SELECT ou.ts_id ,ou.common_name,cts.subtype
        FROM ad_org_unit ou   
		left join traffic_source cts on cts.ts_id = ou.ts_id
        where ou.ts_id not in(select ts_id_child from ad_org_unit cous 
        left join  traffic_source_contains ctsc on cous.ts_id =  ctsc.ts_id_child
        left join traffic_source cts on cts.ts_id =  cous.ts_id
         where  ts_id_child is not null)";
	
    return  DB::connection('pgsql3')->select($stat2)  ;
    }

    public function show($id)
    {
        $stat2 = "SELECT ou.ts_id ,obj.common_name ,
        tsc.ts_id_child ,tsc.ts_id_parent ,subtype,'1' as int_type
        ,(select count(1) from traffic_source_contains  
	  where ts_id_parent = tsc.ts_id_child)as child
        FROM ad_org_unit ou   
    left join  traffic_source_contains tsc on ou.ts_id =  tsc.ts_id_parent
    left join  ad_org_unit obj on obj.ts_id =  tsc.ts_id_child
    left join traffic_source ts on ts.ts_id =  tsc.ts_id_child
    where ts.subtype = 'OrgUnit' and ou.ts_id =?
    union 
    SELECT ou.ts_id ,obj.common_name ,
        tsc.ts_id_child ,tsc.ts_id_parent ,subtype,'2' as int_type
        ,(select count(1) from traffic_source_contains  
	  where ts_id_parent = tsc.ts_id_child)as child
        FROM ad_org_unit ou   
    left join  traffic_source_contains tsc on ou.ts_id =  tsc.ts_id_parent
    left join  ad_group obj on obj.ts_id =  tsc.ts_id_child
    left join traffic_source ts on ts.ts_id =  tsc.ts_id_child
    where ts.subtype = 'Group' and ou.ts_id =?
    union 
    SELECT ou.ts_id ,obj.common_name ,
        tsc.ts_id_child ,tsc.ts_id_parent ,subtype,'3' as int_type,'0' as child
        FROM ad_org_unit ou   
    left join  traffic_source_contains tsc on ou.ts_id =  tsc.ts_id_parent
    left join  ad_user obj on obj.ts_id =  tsc.ts_id_child
    left join traffic_source ts on ts.ts_id =  tsc.ts_id_child
    where ts.subtype = 'User' and ou.ts_id =?
    union 
    SELECT ou.ts_id ,obj.common_name ,
        tsc.ts_id_child ,tsc.ts_id_parent ,subtype,'4' as int_type,'0' as child
        FROM ad_org_unit ou   
    left join  traffic_source_contains tsc on ou.ts_id =  tsc.ts_id_parent
    left join  ad_computer obj on obj.ts_id =  tsc.ts_id_child
    left join traffic_source ts on ts.ts_id =  tsc.ts_id_child
    where ts.subtype = 'Computer' and ou.ts_id =?
    order by int_type asc";
        //ts.subtype = 'OrgUnit' and
       // $AD_OusInfo['policycontainer'] = ModelTreatment::getpolicyouse($id);
       return  DB::connection('pgsql3')->select($stat2,[$id,$id,$id,$id])  ;
    }
    public function update($prefix, Request $request, AD_Ous_ts $model)
    {
        //  return $this->rUpdate($this->m, $model, $request->all(), $this->pk);
        return $this->sUpdate($this->m, $model, $request->all(), $this->pk, $prefix);
    }
    public function store(Request $request)
    {
        return $this->rStore($this->m, $request, $this->pk);
    }
    public function destroy($prefix, AD_Ous_ts $model)
    {

        $model->destroy($prefix);

        return ['status' => 0];
    }
  
    public function search(Request $request)
    {
        $id = $request->get('ts_id');
        $type = $request->get('type');
        $str = $request->get('str');
        if ($type === 'OrgUnit') {
          return TreeTreatment::getous($id);
        }
      if($type === 'Group'){
       return TreeTreatment::getgroups($id);
      }
      if($type === 'User'){
        return TreeTreatment::getusers($id,$str);
       }
       if($type === 'Computer'){
        return TreeTreatment::getcomputers($id,$str);
       }

    }
    public function itemsList($mode, $search = '')
    {
        $AD_Ous_ts = DB::table('ad_org_unit')
            ->select('ts_id', DB::raw("CONCAT(common_name, ' ', ts_id) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'ts_id') {
            $AD_Ous_ts = $AD_Ous_ts->where('ts_id', $search);
        } else if ($mode == 'phrase') {
            $AD_Ous_ts = $AD_Ous_ts->where(DB::raw("CONCAT(common_name, ' ', ts_id)"), 'like', '%' . $search . '%');
        }
        return $AD_Ous_ts->take(100)->get();
    }
}
