<?php

namespace App\Libraries;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait TreeTreatment
{

    public  static function getous($id)
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
SELECT  ou.ts_id ,(select count(1) from traffic_source_contains  tsc1 
  left join traffic_source ts1 on ts1.ts_id =  tsc1.ts_id_child
  where ts_id_parent = ou.ts_id 
  and ts1.subtype = 'User'
  )::char as common_name,
  floor(random() * 1000000 + 1) as ts_id_child,ou.ts_id as ts_id_parent ,
'User' as subtype,'3' as int_type,'0' as child
    
    FROM ad_org_unit ou   
  where ou.ts_id =?
union 
SELECT ou.ts_id ,obj.common_name ,
    tsc.ts_id_child ,tsc.ts_id_parent ,subtype,'4' as int_type,'0' as child
    FROM ad_org_unit ou   
left join  traffic_source_contains tsc on ou.ts_id =  tsc.ts_id_parent
left join  ad_computer obj on obj.ts_id =  tsc.ts_id_child
left join traffic_source ts on ts.ts_id =  tsc.ts_id_child
where ts.subtype = 'Computer' and ou.ts_id =?
order by int_type asc";
        
        return  DB::connection('pgsql')->select($stat2, [$id, $id, $id, $id]);
    }

    public  static function getgroups($id){
        $stat2 = " SELECT grp.ts_id ,obj.common_name ,
        tsc.ts_id_child ,tsc.ts_id_parent ,subtype,'2' as int_type
        ,(select count(1) from traffic_source_contains  
	  where ts_id_parent = tsc.ts_id_child)as child
        FROM ad_group grp   
    left join  traffic_source_contains tsc on grp.ts_id =  tsc.ts_id_parent
    left join  ad_group obj on obj.ts_id =  tsc.ts_id_child
    left join traffic_source ts on ts.ts_id =  tsc.ts_id_child
    where ts.subtype = 'Group' and grp.ts_id =?
    union 
    SELECT grp.ts_id ,obj.common_name ,
        tsc.ts_id_child ,tsc.ts_id_parent ,subtype,'3' as int_type,'0' as child
        FROM ad_group grp   
    left join  traffic_source_contains tsc on grp.ts_id =  tsc.ts_id_parent
    left join  ad_user obj on obj.ts_id =  tsc.ts_id_child
    left join traffic_source ts on ts.ts_id =  tsc.ts_id_child
    where ts.subtype = 'User' and grp.ts_id =?
    union 
    SELECT grp.ts_id ,obj.common_name ,
        tsc.ts_id_child ,tsc.ts_id_parent ,subtype,'4' as int_type,'0' as child
        FROM ad_group grp   
    left join  traffic_source_contains tsc on grp.ts_id =  tsc.ts_id_parent
    left join  ad_computer obj on obj.ts_id =  tsc.ts_id_child
    left join traffic_source ts on ts.ts_id =  tsc.ts_id_child
    where ts.subtype = 'Computer' and grp.ts_id =?
    order by int_type asc";
        
            return  DB::connection('pgsql')->select($stat2, [$id,$id,$id])  ;

    }
    public  static function getusers($id,$str){
        $stat2 = "  SELECT obj.common_name ,
        tsc.ts_id_child ,tsc.ts_id_parent ,subtype,'3' as int_type,'0' as child
        FROM traffic_source_contains tsc   
    left join  ad_user obj on obj.ts_id =  tsc.ts_id_child
    left join traffic_source ts on ts.ts_id =  tsc.ts_id_child
    where ts.subtype = 'User' and tsc.ts_id_parent =?
    and LOWER(obj.common_name) like LOWER(?)
	order by obj.common_name asc
	limit 250";
        
            return  DB::connection('pgsql')->select($stat2,
             [
             $id,
             '%'.$str.'%'
             ])  ;

    }
    public  static function getcomputers($id,$str){
        $stat2 = "  SELECT obj.common_name ,
        tsc.ts_id_child ,tsc.ts_id_parent ,subtype,'4' as int_type,'0' as child
        FROM traffic_source_contains tsc    
    left join  ad_computer obj on obj.ts_id =  tsc.ts_id_child
    left join traffic_source ts on ts.ts_id =  tsc.ts_id_child
    where ts.subtype = 'Computer' and tsc.ts_id_parent =?
    and LOWER(obj.common_name) like LOWER(?)
	order by obj.common_name asc
	limit 250";
        
            return  DB::connection('pgsql')->select($stat2,
             [
             $id,
             '%'.$str.'%'
             ])  ;

    }
}
