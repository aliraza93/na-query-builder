<?php

namespace App\Libraries;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait ModelTreatment
{
    public static function getAsyncData($model, $request, $columns, $connection, $table, $defaultSortColumn, $defaultOrder)
    { 

        $rowsPerPage = $request->get('rowsPerPage');
        $sortBy = $request->get('sortBy');
        $sortDesc = $request->get('sortDesc');
        if ($request->has('parent') && $request->get('parent') !== null){
           $parent = $request->get('parent');
           if (isset($parent['type'])) {
               $type = $parent['type'];
           }else{
               $type =null ;
           }
           if($type !== null){
            $ts_id_parent = $parent['ts_id_parent'];

            $model ->leftJoin(
                'traffic_source_contains',
                'traffic_source_contains.ts_id_child',
                '=',
                $table.'.ts_id'
            )->select($table.'.*','traffic_source_contains.ts_id_parent')
            ->where("ts_id_parent", $ts_id_parent);


           }else{
            $idstr = $parent['id'];

            if (strpos($idstr, '-') !== false) {
                $id = substr($idstr, strpos($idstr, "-") + 1);
            }else{
                $id= $parent['id'];
            }
            $model->where("ts_id", $id);
           }


       
        
       }

        $search = $request->get('search');
        $searchPhrases = explode(" ", $search);
        $filterColumns = $request->get('filterColumns');
        $deleteMode = $request->get('deleteMode');
        $activeColumnName = $request->get('activeColumnName');
        if (in_array($deleteMode, ['soft', 'both'])) {
            $selectedStatuses = $request->get('selectedStatuses');
            $model->whereIn($activeColumnName, $selectedStatuses);
        }
        if ($search != '') {
            foreach ($searchPhrases as $searchPhrase) {
                $model->where(function ($query) use ($columns, $searchPhrase,$table) {
                    for ($i = 0; $i < count($columns); $i++) {
                        if ($i == 0) {
                            //'cast('.$col .'as varchar)'  $columns[$i]
                            //$query->whereRaw( $columns[$i] , 'like', '%' . $searchPhrase . '%');
                            //$query->whereRaw("cast( " . $columns[$i] . " as varchar) like '%" . $searchPhrase . "%'");
                            $query->whereRaw("cast( " .$table.".". $columns[$i] . " as varchar) like ? ",['%' . $searchPhrase . '%']);

                        } else {
                            // $query->orwhereRaw('cast(' . $columns[$i] . ' as varchar)', 'like', '%' . $searchPhrase . '%');
                            // $query->orwhereRaw("cast( " . $columns[$i] . " as varchar) like '%" . $searchPhrase . "%'");
                            $query->orwhereRaw("cast( " .$table.".". $columns[$i] . " as varchar) like ? ", ['%' . $searchPhrase . '%']);

                        }
                    }
                });
            }
        }
        foreach ($filterColumns as $filterColumn) {
            if ($filterColumn['value'] != null && in_array($filterColumn['name'], $columns)) {
                $val = $filterColumn['value'];
                $col = $filterColumn['name'];
                $mode = $filterColumn['mode'];
                $model->where(function ($query) use ($col, $val, $mode) {
                    switch ($mode) {
                            //CAST(25.65 AS varchar); whereRaw('views > ? and votes > ?', [500, 50])
                            //>whereRaw('cast(' . $col . ' as varchar)', 'like', '%' . $val . '%');
                        case 'like':

                            $query->whereRaw("cast( " . $col . " as varchar) like ? ",  ['%' .$val. '%']);
                            break;
                        case 'equals':
                            $query->where($col, '=', $val);
                            break;
                        case 'list':
                            $tmpList = explode(";", $val);
                            $query->whereIn($col, $tmpList);
                            break;
                        default:
                            break;
                    }
                });
            }
        }
        if ($sortBy != null && $sortDesc != null) {

            for ($i = 0; $i < count($sortBy); $i++) {
                $direction = $sortDesc[$i] == 'true' ? 'desc' : 'asc';
                $model->orderBy($sortBy[$i], $direction);

            }
        } else {

            $model->orderBy($defaultSortColumn, $defaultOrder);
        }
        return $model->paginate($rowsPerPage);
    }
    
public  static function getpolicyuser($id){
        $statement = "select  tsp.ts_id,ad_user.common_name,'User' as subtype,tsp.policy_id,plc.policy_name,
        tsp.priority_policy,plc.priority,'true' as direct,'Direct' as inhertblock,tsp.enforced_flag
from traffic_source_policy tsp
left join ad_user on tsp.ts_id = ad_user.ts_id 
left join policy plc on tsp.policy_id = plc.policy_id
where tsp.ts_id  =?
union  SELECT DISTINCT on (vcf.ts_id_root,tsp.policy_id) ts_id_root, 
  common_name_root as common_name,subtype_root
 ,plc.policy_id,plc.policy_name,tsp.priority_policy,plc.priority,'false' as direct,
 CASE WHEN is_blocked = true then 'Blocked' else 'Inherted' END as inhertblock,tsp.enforced_flag
 FROM v_contains_full vcf 
 left join traffic_source_policy tsp on tsp.ts_id = vcf.ts_id_root
 left join policy plc on tsp.policy_id = plc.policy_id
 where  plc.policy_id is not null and vcf.ts_id_child =? ";
        $db = DB::connection('pgsql3');
        $policy = $db->select($statement, [$id, $id]);
        return $policy;

}

    public  static function getpolicycomputer($id)
    {
        $statement = "select  tsp.ts_id,ad_computer.common_name,'Computer' as subtype,tsp.policy_id,plc.policy_name,
        tsp.priority_policy,plc.priority,'true' as direct,'Direct' as inhertblock,tsp.enforced_flag
from traffic_source_policy tsp
left join ad_computer on tsp.ts_id = ad_computer.ts_id 
left join policy plc on tsp.policy_id = plc.policy_id
where tsp.ts_id  =?
union  SELECT DISTINCT on (vcf.ts_id_root,tsp.policy_id) ts_id_root, 
  common_name_root as common_name,subtype_root
 ,plc.policy_id,plc.policy_name,tsp.priority_policy,plc.priority,'false' as direct,
 CASE WHEN is_blocked = true then 'Blocked' else 'Inherted' END as inhertblock,tsp.enforced_flag
 FROM v_contains_full vcf 
 left join traffic_source_policy tsp on tsp.ts_id = vcf.ts_id_root
 left join policy plc on tsp.policy_id = plc.policy_id
 where  plc.policy_id is not null and vcf.ts_id_child =? ";
        $db = DB::connection('pgsql3');
        $policy = $db->select($statement, [$id, $id]);
        return $policy;

    }

    public  static function getpolicysubnet($id)
    {
        $statement = "select  tsp.ts_id,ip_address.name as common_name,'IP' as subtype,tsp.policy_id,plc.policy_name,
        tsp.priority_policy,plc.priority,'true' as direct,'Direct' as inhertblock,tsp.enforced_flag
from traffic_source_policy tsp
left join ip_address on tsp.ts_id = ip_address.ts_id 
left join policy plc on tsp.policy_id = plc.policy_id
where tsp.ts_id  =?
union  SELECT DISTINCT on (vcf.ts_id_root,tsp.policy_id) ts_id_root, 
  common_name_root as common_name,subtype_root
 ,plc.policy_id,plc.policy_name,tsp.priority_policy,plc.priority,'false' as direct,
 CASE WHEN is_blocked = true then 'Blocked' else 'Inherted' END as inhertblock,tsp.enforced_flag
 FROM v_contains_full vcf 
 left join traffic_source_policy tsp on tsp.ts_id = vcf.ts_id_root
 left join policy plc on tsp.policy_id = plc.policy_id
 where  plc.policy_id is not null and vcf.ts_id_child =? ";
        $db = DB::connection('pgsql3');
        $policy = $db->select($statement, [$id, $id]);
        return $policy;

    }

    public  static function getpolicygrps($id)
    {
        $statement = "select  tsp.ts_id,ad_group.common_name,'Group' as subtype,tsp.policy_id,plc.policy_name,
        tsp.priority_policy,plc.priority,'true' as direct,'Direct' as inhertblock,tsp.enforced_flag
from traffic_source_policy tsp
left join ad_group on tsp.ts_id = ad_group.ts_id 
left join policy plc on tsp.policy_id = plc.policy_id
where tsp.ts_id  =?
union  SELECT DISTINCT on (vcf.ts_id_root,tsp.policy_id) ts_id_root, 
  common_name_root as common_name,subtype_root
 ,plc.policy_id,plc.policy_name,tsp.priority_policy,plc.priority,'false' as direct,
 CASE WHEN is_blocked = true then 'Blocked' else 'Inherted' END as inhertblock,tsp.enforced_flag
 FROM v_contains_full vcf 
 left join traffic_source_policy tsp on tsp.ts_id = vcf.ts_id_root
 left join policy plc on tsp.policy_id = plc.policy_id
 where  plc.policy_id is not null and vcf.ts_id_child =? ";
        $db = DB::connection('pgsql3');
        $policy = $db->select($statement, [$id, $id]);
        return $policy;

    }

    public  static function getpolicycontainer($id)
    {
        $statement = "select  tsp.ts_id,container.common_name,'Container' as subtype,tsp.policy_id,plc.policy_name,
        tsp.priority_policy,plc.priority,'true' as direct,'Direct' as inhertblock,tsp.enforced_flag
from traffic_source_policy tsp
left join container on tsp.ts_id = container.ts_id 
left join policy plc on tsp.policy_id = plc.policy_id
where tsp.ts_id  =?
union  SELECT DISTINCT on (vcf.ts_id_root,tsp.policy_id) ts_id_root, 
  common_name_root as common_name,subtype_root
 ,plc.policy_id,plc.policy_name,tsp.priority_policy,plc.priority,'false' as direct,
 CASE WHEN is_blocked = true then 'Blocked' else 'Inherted' END as inhertblock,tsp.enforced_flag
 FROM v_contains_full vcf 
 left join traffic_source_policy tsp on tsp.ts_id = vcf.ts_id_root
 left join policy plc on tsp.policy_id = plc.policy_id
 where  plc.policy_id is not null and vcf.ts_id_child =? ";
        $db = DB::connection('pgsql3');
        $policy = $db->select($statement, [$id, $id]);
        return $policy;

    }
    public  static function getpolicyouse($id)
    {
        $statement = "select  tsp.ts_id,ad_org_unit.common_name,'OrgUnit' as subtype,tsp.policy_id,plc.policy_name,
        tsp.priority_policy,plc.priority,'true' as direct,'Direct' as inhertblock,tsp.enforced_flag
from traffic_source_policy tsp
left join ad_org_unit on tsp.ts_id = ad_org_unit.ts_id 
left join policy plc on tsp.policy_id = plc.policy_id
where tsp.ts_id  =?
union  SELECT DISTINCT on (vcf.ts_id_root,tsp.policy_id) ts_id_root, 
  common_name_root as common_name,subtype_root
 ,plc.policy_id,plc.policy_name,tsp.priority_policy,plc.priority,'false' as direct,
 CASE WHEN is_blocked = true then 'Blocked' else 'Inherted' END as inhertblock,tsp.enforced_flag
 FROM v_contains_full vcf 
 left join traffic_source_policy tsp on tsp.ts_id = vcf.ts_id_root
 left join policy plc on tsp.policy_id = plc.policy_id
 where  plc.policy_id is not null and vcf.ts_id_child =? ";
        $db = DB::connection('pgsql3');
        $policy = $db->select($statement, [$id, $id]);
        return $policy;;
    }
   
}
