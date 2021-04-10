<?php

namespace App\Http\Controllers\AD;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Adldap\Laravel\Facades\Adldap;

use Adldap\AdldapInterface;
use Adldap\Models\RootDse;
use App\Models\Libraries\ADTreatMapDB;
use App\Models\AD\AD_Users;
use App\Models\AD\AD_Groups;
use App\Models\AD\AD_Ous;
use App\Models\AD\AD_Computer;
use App\Models\AD\AD_Valuestore;
use App\Models\AD\GroupinGroup;
use App\Models\AD\UsersinGroup;
use App\Models\AD\ComputersinGroup;
use App\Models\AD\AD_LdapConnection;
use App\Models\AD\ObjectinContainer;
use Illuminate\Support\Facades\DB;


//GroupinGroup

class SyncAllController extends Controller
{
    private $musers = AD_Users::class;
    private $mgrps = AD_Groups::class;
    private $mous = AD_Ous::class;
    private $mcomputer = AD_Computer::class;
    private $mvaluestore = AD_LdapConnection::class;
    private $mgrpingrp = GroupinGroup::class;
    private $musersingrp  = UsersinGroup::class;
    private $mcompuersingrp = ComputersinGroup::class;
    private $mousinous = ObjectinContainer::class;


    /**
     * 
     * @var Adldap
     */
    protected $ldap;
    protected $basedn;
    protected $provider = null;
    protected $notsync = null; 
    protected  $errors =null;

    /**
     * Constructor.
     *
     * @param AdldapInterface $adldap
     */
    public function __construct(AdldapInterface $ldap)
    {
        set_time_limit(1200);

        //$this->ldap = $ldap;

        $this->middleware('role:read', ['only' => ['index', 'usn', 'search']]);
        $this->middleware('role:insert', ['only' => ['store', 'multipleAdd']]);
        $this->middleware('role:update', ['only' => ['update', 'multipleUpdate']]);
        $this->middleware('role:delete', ['only' => ['destroy']]);
    }


    /**
     * Displays the all LDAP users.
     *
     * @return \Illuminate\View\View
     */
    public function getgroupbydn($param)
    {
        $groups = null;

        foreach ($param->getMemberOf() as $dn) {
            $group =  $this->ldap->search()->findByDn($dn);
            //->select('cn', 'objectguid', 'memberof')

            $groups[] = $group->getConvertedGuid();
        }
        return $groups;
    }
    public function index()
    {
     

       
        $statement = "select cp.ts_id as ts_id_parent, cc.ts_id as ts_id_child
        from ad_org_unit cp
        inner join ad_org_unit cc
        on cc.object_guid = ?
        where cp.object_guid = ?";
        $db =  DB::connection('pgsql3');
        
        
        try {
            $sync_status = [];
           
            $count = AD_LdapConnection::count();
            if (!$count > 0) {
                return ['status' => -2, 'msg' => 'Can not find Ldap server configuration'];
               
            }

            $adconfig =  AD_LdapConnection::first();
            $config =[];
            $config['hosts'] =   [$adconfig->hosts];
            $config['base_dn'] =   $adconfig->base_dn;
            $config['username'] =   $adconfig->username;
            $config['password'] =   $adconfig->password;
            $config['account_prefix'] =   $adconfig->account_prefix;
            $config['account_suffix'] =   $adconfig->account_suffix;
            $config['port'] =   $adconfig->port;
            $config['follow_referrals'] =   $adconfig->follow_referrals;
            $config['use_ssl'] =   $adconfig->use_ssl;
            $config['use_tls'] =   $adconfig->use_tls;
            $config['version'] =  3;
            $config['timeout'] =   $adconfig->timeout;
            $this->basedn =   $adconfig->base_dn;
         
            $ldapconection =   ADTreatMapDB::ldapconect($config);
            if (is_array($ldapconection)) {
                if ($ldapconection['status'] == 0) {
                   $this->ldap = $ldapconection['ldap'];
                }
                if ($ldapconection['status'] != 0) {
                    return  $ldapconection;
                }
            } else {
                return  $ldapconection;
            }
     
             $allusers = $this->getusers();
            $allous = $this->getous();
            $allgrp = $this->getgroups();
            $allcomputers = $this->getcomputers();
            $rootDse =  $this->ldap->search()->getRootDse();
            $rootbase =  $rootDse->getRootDomainNamingContext() ;
            $rootobject =  $this->ldap->search()->findByDn($rootbase);
            $rootous['object_guid'] = $rootobject->getConvertedGuid();
            $rootous['when_created'] = $rootobject->getCreatedAtDate();
            $rootous['when_changed'] = $rootobject->getUpdatedAtDate();
            $rootous['obj_dist_name'] =$rootobject['distinguishedname'][0];
            $rootous['common_name'] = $rootobject['name'][0];
            array_push($allous[0], $rootous);
                  
            $pk = 'object_guid';
            $runssync = ADTreatMapDB::mstore($allous, $this->mous, $pk);
            if (is_array($runssync)) {
                if ($runssync['status'] != 0) {
                    return  $runssync;
                }
            } else {
                return  $runssync;
            }
            $sync_status[] = ['sync_ous' => 'success'];
       

            //sync groups
            $pk = 'object_guid';
            $runssync = ADTreatMapDB::mstore(
                $allgrp,
                $this->mgrps,
                $pk
            );
            if (is_array($runssync)) {
                if ($runssync['status'] != 0) {
                    return  $runssync;
                }
            } else {
                return  $runssync;
            }
            $sync_status[] = ['sync_groups' => 'success'];


            //sync users
            $pk = 'object_guid';
            $runssync = ADTreatMapDB::mstore($allusers, $this->musers, $pk);
            if (is_array($runssync)) {
                if ($runssync['status'] != 0) {
                    return  $runssync;
                }
            } else {
                return  $runssync;
            }
            $sync_status[] = ['sync_users' => 'success'];



            //sync computers
            $pk = 'object_guid';
            $runssync = ADTreatMapDB::mstore($allcomputers, $this->mcomputer, $pk);
            if (is_array($runssync)) {
                if ($runssync['status'] != 0) {
                    return  $runssync;
                }
            } else {
                return  $runssync;
            }
            $sync_status[] = ['sync_computers' => 'success'];

            //sync groups in group
            $op_grp_ou = false;
            
           $grpstatus =  $this->objinboj($allgrp,$this->mgrpingrp, $op_grp_ou);
           if ($grpstatus !== true) {
        if (is_array($grpstatus)) {
          if ($grpstatus['status'] != 0) {
            return  $grpstatus;
          }
        } else {
          return  $grpstatus;
        }
           }
           
           
          
            $sync_status[] = ['sync_groups_in_groups' => 'success'];

            //sync users in group  objinbojbychild
            $op_grp_ou = false;

            $userstatus =  $this->objinbojbychild($allusers, $this->musersingrp, $op_grp_ou, 'user');
            if ($userstatus !== true) {
                if (is_array($userstatus)) {
                    if ($userstatus['status'] != 0) {
                        return  $userstatus;
                    }
                } else {
                    return  $userstatus;
                }
            }

         
            $sync_status[] = ['sync_users_in_groups' => 'success'];

      // sync computer in groups mcompuersingrp
      
      $op_grp_ou =false;
            $comstatus =  $this->objinbojbychild($allcomputers, $this->mcompuersingrp, $op_grp_ou,'pc');
            if ($comstatus !== true) {
                if (is_array($comstatus)) {
                    if ($comstatus['status'] != 0) {
                        return  $comstatus;
                    }
                } else {
                    return  $comstatus;
                }
            }
                $sync_status[] = ['sync_computers_in_groups' => 'success'];
         
            
            
      //sync ous in ous 
      
      if (is_array($allous)) {
       
            foreach ($allous as $ous) {

                foreach ($ous as $ouobj) {
                
                    $parentou = $ouobj['object_guid'];
                    if ($parentou !== null) {
                        $oubybase =   $this->ldap->search()->ous()->setDn($ouobj['obj_dist_name'])->get();
                      
                        foreach ($oubybase as $ouchild) {
                            if ($ouchild !== null) {
                             
                            $childobj = $ouchild->getConvertedGuid();
                            if ($parentou !== $childobj) {
                                $p = $db->select($statement, [$childobj, $parentou]);
                                if (isset($p[0])) {
                                    if (isset($p[0]->ts_id_parent)) {
                                        $findou =  $db->select('select ts_id_parent from traffic_source_contains where ts_id_parent =? 
                            and ts_id_child =? ', [$p[0]->ts_id_parent, $p[0]->ts_id_child]);
                                        if (count($findou) === 0) {
                                            $db->insert('insert into traffic_source_contains (ts_id_parent, ts_id_child) 
                            values (?, ?)', [$p[0]->ts_id_parent, $p[0]->ts_id_child]);
                                        }
                                    }
                                }
                            }
                         }
                        }
                    }
                 }
            }
        }
            $sync_status[] = ['sync_ous_in_ous' => 'success'];
        
         
            $statement = " INSERT  INTO  traffic_source_contains(ts_id_child,ts_id_parent)
            SELECT obj.ts_id,ou.ts_id FROM ad_user obj
            left join ad_org_unit ou on ou.obj_dist_name =?
            left join traffic_source_contains tc on tc.ts_id_child = obj.ts_id 
            and tc.ts_id_parent = ou.ts_id
            where obj.obj_dist_name like ?
           ON CONFLICT (ts_id_child,ts_id_parent) DO NOTHING";
            $getallous =  $this->mous::get();
            if ($getallous !== null) {
                foreach ($getallous as $oneous) {
                    if ($oneous['obj_dist_name']!== null) {
                        $p = $db->insert(
                            $statement,
                            [
                             $oneous['obj_dist_name'],
                             '%' . $oneous['obj_dist_name'] . '%'
                          ]
                        );
                    }
                }

            
                $sync_status[] = ['sync_users_in_ous' => 'success'];
           
                $statement = " INSERT  INTO  traffic_source_contains(ts_id_child,ts_id_parent)
            SELECT obj.ts_id,ou.ts_id FROM ad_group obj
            left join ad_org_unit ou on ou.obj_dist_name =?
            left join traffic_source_contains tc on tc.ts_id_child = obj.ts_id 
            and tc.ts_id_parent = ou.ts_id
            where obj.obj_dist_name like ?
            ON CONFLICT (ts_id_child,ts_id_parent) DO NOTHING";
            
                foreach ($getallous as $oneous) {
                    if ($oneous['obj_dist_name'] !== null) {
                        $p = $db->insert(
                            $statement,
                            [
                        $oneous['obj_dist_name'],
                        '%' . $oneous['obj_dist_name'] . '%'
                    ]
                        );
                    }
                }
                $sync_status[] = ['sync_groups_in_ous' => 'success'];
               /*
                $teststat = "SELECT obj.ts_id,ou.ts_id FROM ad_computer obj
            left join ad_org_unit ou on ou.obj_dist_name =?
            left join traffic_source_contains tc on tc.ts_id_child = obj.ts_id 
            and tc.ts_id_parent = ou.ts_id
            where obj.obj_dist_name like  ?
			 and tc.ts_id_child not in 
      (select ts_id_child FROM traffic_source_contains where ts_id_parent = ou.ts_id )";
      $testarry =null;
        foreach ($getallous as $oneous) {
          if ($oneous['obj_dist_name'] !== null) {
            $p = $db->select(
              $teststat,
              [
                $oneous['obj_dist_name'],
                '%' . $oneous['obj_dist_name'] . '%'
              ]
            );
          }
           $testarry[]= $p;

        }
        return $testarry;*/

              
            
           $statement = " INSERT  INTO  traffic_source_contains(ts_id_child,ts_id_parent)
            SELECT obj.ts_id,ou.ts_id FROM ad_computer obj
            left join ad_org_unit ou on ou.obj_dist_name =?
            left join traffic_source_contains tc on tc.ts_id_child = obj.ts_id 
            and tc.ts_id_parent = ou.ts_id
            where obj.obj_dist_name like ?
            ON CONFLICT (ts_id_child,ts_id_parent) DO NOTHING";

                foreach ($getallous as $oneous) {
                    if ($oneous['obj_dist_name'] !== null) {
                        $p = $db->insert(
                            $statement,
                            [
                        $oneous['obj_dist_name'],
                        '%' . $oneous['obj_dist_name'] . '%'
                    ]
                        );
                    }
                }
                $sync_status[] = ['sync_computers_in_ous' => 'success'];
                
                
            }
            

          /*
            
            $ctrl1 = ["oid" => "1.2.840.113556.1.4.417", "iscritical" => true];

            $options = [LDAP_OPT_SERVER_CONTROLS => [$ctrl1]];
              $this->ldap->getDefaultProvider()->getConnection()->setOptions($options);
             $this->ldap->connect();
              $obj = null;
            $obj =  $this->ldap->getProvider('default')->search()->where('isdeleted', 'TRUE')->get();
            if ($obj !== null) {
                $this->handeldeletde($obj);

                $sync_status[] = ['sync_deleted-' => 'success'];
            }
            
            */

           



            //$rootDse['highestcommittedusn'][0]

            $highest = ['highest_committed_usn' => $rootDse['highestcommittedusn'][0]];
            $model = $this->mvaluestore;
            $newvalues = $model::where('highest_committed_usn', '>=', '0')->first();
            if ($newvalues !== null) {

                $newvalues->update($highest);
            } 

            $sync_status[] = $highest;

           
            return ['status' => 0, "success" => 'active directory sync full', "sync_status" => $sync_status, ];

        } catch (\Exception $e) {

         //   $results =  ['status' => -2, 'msg' => $e->getMessage(), "sync_status" => $sync_status];
      return response()->json(['status' => -2, "msg" => $e->getMessage(), "sync_status" => $sync_status], 200);


            // something went wrong
        } catch (\Adldap\Auth\BindException $e) {
            
            return ['status' => -2, 'msg' => $e->getMessage()];
         
        }
        // return [$allusers, $all,$ou,$computers, $rootDse['highestcommittedusn']];
    }

    public function objinbojbychild($allobj,$model,$action =false,$reqtable)
    {
      try {
          $cruentobj = [];
          if (!is_array($allobj)) {
              return true;
          }
          foreach ($allobj as $users) {

        if (is_array($users)) {
         
          foreach ($users as $user) {
         
                  $user_gruid = $user['object_guid'];
                  $memberof =  $user['memberof'];
                  $cruentobj[] = $user_gruid;

            //   return [$memberof, $user_gruid];
            $check_user = null;

            $model::where('object_guid_child', $user_gruid)->delete();
            if ($reqtable === 'user'
            ) {
              $check_user = $this->musers::where('object_guid', $user_gruid)->first();
            }
            if ($reqtable === 'pc') {
              $check_user = $this->mcomputer::where('object_guid', $user_gruid)->first();
            }

                  if ($memberof != null) {
                      $grpingrp = [];
                      $newval = [];
                      if (is_array($memberof)) {
                          foreach ($memberof as $grpin) {
                              if ($action === false) {
                                  $check_grp = $this->mgrps::where('object_guid', $grpin)->first();
                                  if ($check_grp !== null) {
                                       if ($check_user !==null) {
                                           $grpingrp[] = ['object_guid_parent' => $grpin, 'object_guid_child' => $user_gruid];
                                           $newval[] = $grpin;
                                       }
                                  }
                              } else {
                                  $grpingrp[] = ['object_guid_parent' => $grpin, 'object_guid_child' => $user_gruid];
                                  $newval[] = $grpin;
                              }
                          }
                          $pk = ['object_guid_parent', 'object_guid_child'];
                          $runssync = ADTreatMapDB::mstorewithoutupdate(
                              [$grpingrp],
                              $model,
                              $pk
                          );
                          if (is_array($runssync)) {
                              if ($runssync['status'] != 0) {
                                  return  $runssync;
                              }
                          } else {
                              return  $runssync;
                          }
                          
                         /* if (count($newval) > 0) {
                              $model::where('object_guid_child', $user_gruid)
                            ->where(function ($query) use ($newval) {
                                $query->whereNotIn('object_guid_parent', $newval);
                            })->delete();
                          } else {
                              $model::where('object_guid_child', $user_gruid)->delete();
                          }*/
                          
                      }
                  }
              }
            //  $model::whereNotIn('object_guid_child', $cruentobj)->delete();
          }
      }
          return true;
       } catch (\Exception $e) {
            $results =  ['status' => -2, 'msg' => $e->getMessage(),'funchildinobj'];

            return $results;

            // something went wrong
        }

    }
     public function objinboj($allobj,$model,$action =false)
    {
      try {
        //    $db =  DB::connection('pgsql3');
      //$model::where('object_guid_parent', "!=", "123e4567-e89b-12d3-a456-426652340000")->delete();
         $allgrpingrp=null;
          $cruentobj = [];
      if (!is_array($allobj)) {
        return true;
      }
          foreach ($allobj as $objs) {
               if ($objs !== null) {
                   foreach ($objs as $obj) {
                       $childobj = $obj['object_guid'];
                       $cruentobj[] = $childobj;

                       $memberof =  $obj['memberof'];
                       $model::where('object_guid_child', $childobj)->delete();
                       $check_grp = null;
                       $check_grp = $this->mgrps::where('object_guid', $childobj)->first();
 
                       //return [$memberof, $group, $parent_gruid];
                       if ($memberof !== null) {
                           $objinobj = [];
                           $newval = [];
                       
                           foreach ($memberof as $parent_gruid) {
                               if ($action === false) {
                                   if ($check_grp !== null) {
                                       $check_grp_c = null;
                                       $check_grp_c= $this->mgrps::where('object_guid', $parent_gruid)->first();
                                       if ($check_grp_c !== null) {
                                           if ($childobj !== null && $parent_gruid !== null) {
                                               $objinobj[] = ['object_guid_parent' => $parent_gruid, 'object_guid_child' => $childobj];
                                               $newval[] = $parent_gruid;
                                           }
                                       }
                                   }
                               }
                           }
                           $allgrpingrp[]= $objinobj;

                    
                           $pk = ['object_guid_parent', 'object_guid_child'];
                           if (count($objinobj) > 0) {
                               $runssync = ADTreatMapDB::mstorewithoutupdate(
                                   [$objinobj],
                                   $model,
                                   $pk
                               );
          
                               if (is_array($runssync)) {
                                   if ($runssync['status'] != 0) {
                                       return  $runssync;
                                   }
                               } else {
                                   return  $runssync;
                               }
                           }
                       }
                   }
            
                   // $model::whereNotIn('object_guid_parent', $cruentobj)->delete();
               }
          }
          return true;
       
          
        }    
      catch (\Exception $e) {
           
      $results =  ['status' => -2, 'msg' => $e->getMessage(),'fungrpinobj'];

      return $results;

      // something went wrong
    }


    }

    public function getous($usn = null, $from = null, $to = null)
    {

        $mapattous = [
            'ou' => 'common_name',
            'distinguishedname' => 'obj_dist_name',
            'whencreated' => 'when_created',
            'whenchanged' => 'when_changed',
            'objectguid' => 'object_guid',
            'usnchanged' => 'usnchanged',

        ];
        $attrous = [
            'ou',
            'distinguishedname',
            'usnchanged',
            'whencreated',
            'whenchanged',
            'objectguid',
            'usnchanged',

        ];
        $recordsPerPage = 100;
        $currentPage = 0;
        if ($usn == 'usn') {

            $search =  $this->ldap->search()->ous()->whereBetween('usnchanged', [$from, $to]);
        } else {
            $search =  $this->ldap->search()->ous();
        }


        $paginator = $search->select($attrous)->paginate($recordsPerPage, $currentPage);
        $total =  $paginator->getPages();
        $current = $paginator->getCurrentPage();
        $paginator->getPerPage();
        $count = $paginator->count();
        $i = 0;
        $all = [];
        $rec = [];
        $recdb = null;
        for ($i = 0; $i < $total; $i++) {
            foreach ($paginator as $result) {
                $rec[] = $result;
                $maprec = [];
                foreach ($mapattous as $Key => $value) {
                    if ($Key == 'objectguid') {
                        $maprec[$value] = $result->getConvertedGuid();
                    } elseif ($Key == 'objectsid') {
                        $maprec[$value] = $result->getConvertedSid();
                    } elseif ($Key == 'lastlogon') {
                        $lastlogon = $result['lastlogon'][0];
                        $win_time = $lastlogon;
                        $unix_time = round($win_time / 10000000) - 11644477200;
                        $lastlogon = date("Y-m-d H:i:s", $unix_time);
                        $maprec[$value] = $lastlogon;
                    } elseif ($Key == 'whencreated') {
                        $maprec[$value] = $result->getCreatedAtDate();
                    } elseif ($Key == 'whenchanged') {
                        $maprec[$value] = $result->getUpdatedAtDate();
                    } elseif ($Key == 'memberof') {
                        $maprec[$value] = $result[$Key];
                    } else {
                        $maprec[$value] = is_array($result[$Key]) ? $result[$Key][0] : $result[$Key];
                       
                    }
                }
                $recdb[] = $maprec;
            }

            $all[] = $recdb;

            $rec = [];

            $currentPage = $currentPage + 1;
            $paginator = $search->select($attrous)->paginate($recordsPerPage, $currentPage);
        }
        return $all;
    }
    public function getgroups($usn = null, $from = null, $to = null)
    {

        $mapattgrp = [
            'CN' => 'common_name',
            'distinguishedname' => 'obj_dist_name',
            'whencreated' => 'when_created',
            'whenchanged' => 'when_changed',
            'objectguid' => 'object_guid',
            'memberof' => 'memberof',
            'usnchanged' => 'usnchanged',


        ];
        $attrgrp = [
            'CN',
            'distinguishedname',
            'usnchanged',
            'whencreated',
            'whenchanged',
            'objectguid',
            'objectsid',
            'memberof',
            'usnchanged',

        ];
        $recordsPerPage = 100;
        $currentPage = 0;
        if ($usn == 'usn') {

            $search =  $this->ldap->search()->groups()->whereBetween('usnchanged', [$from, $to]);
        } else {
            $search =  $this->ldap->search()->groups();
        }

        $paginator = $search->select($attrgrp)->paginate($recordsPerPage, $currentPage);
        $total =  $paginator->getPages();
        $current = $paginator->getCurrentPage();
        $paginator->getPerPage();
        $count = $paginator->count();
        $i = 0;
        $all = null;
        $rec = null;
        $recdb =null;
        
        for ($i = 0; $i < $total; $i++) {
            foreach ($paginator as $result) {
                $rec[] = $result;
                $maprec = [];
                foreach ($mapattgrp as $Key => $value) {
                    if ($Key == 'objectguid') {
                        $maprec[$value] = $result->getConvertedGuid();
                    } elseif ($Key == 'objectsid') {
                        $maprec[$value] = $result->getConvertedSid();
                    } elseif ($Key == 'lastlogon') {
                        $lastlogon = $result['lastlogon'][0];
                        $win_time = $lastlogon;
                        $unix_time = round($win_time / 10000000) - 11644477200;
                        $lastlogon = date("Y-m-d H:i:s", $unix_time);
                        $maprec[$value] = $lastlogon;
                    } elseif ($Key == 'whencreated') {
                        $maprec[$value] = $result->getCreatedAtDate();
                    } elseif ($Key == 'whenchanged') {
                        $maprec[$value] = $result->getUpdatedAtDate();
                    } elseif ($Key == 'memberof') {
                        $maprec[$value] = $this->getgroupbydn($result);
                    } else {
                        $maprec[$value] = is_array($result[$Key]) ? $result[$Key][0] : $result[$Key];
                       
                    }
                }
                $recdb[] = $maprec;
            }

            $all[] = $recdb;

            $rec = null;
    
            $recdb = null;
     

            $currentPage = $currentPage + 1;
            $paginator = $search->select($attrgrp)->paginate($recordsPerPage, $currentPage);
        }
        return $all;
    }

    public function getusers($usn = null, $from = null, $to = null)
    {
        $attruser = [
            'CN',
            'userPrincipalName',
            'physicalDeliveryOfficeName',
            'logoncount',
            'samaccountname',
            'distinguishedname',
            'lastlogon',
            'telephonenumber',
            'department',
            'givenname',
            'displayname',
            'usnchanged',
            'whencreated',
            'whenchanged',
            'name',
            'objectguid',
            'objectsid',
            'mail',
            'useraccountcontrol',
            'memberof',
             'LastName'
        ];

        $mapattuser = [
            'CN' => 'common_name',
            'userPrincipalName' => 'user_principal_name',
            'physicalDeliveryOfficeName' => 'physical_delivery_office_name',
            'logoncount' => 'logon_count',
            'samaccountname' => 'sam_account_name',
            'distinguishedname' => 'obj_dist_name',
            'lastlogon' => 'last_logon',
            'telephonenumber' => 'telephone_number',
            'department' => 'department',
            'givenname' => 'given_name',
            'whencreated' => 'when_created',
            'whenchanged' => 'when_changed',
             'LastName' => 'surname',
            'objectguid' => 'object_guid',
            'mail'=> 'email_addresses',
            'useraccountcontrol' => 'is_enabled',
            'memberof' => 'memberof',
            'usnchanged' => 'usnchanged'

        ];



        $usnchanged = $usn;
        $recordsPerPage = 100;
        $currentPage = 0;
        if ($usn == 'usn') {

            $search =  $this->ldap->search()->users()->whereBetween('usnchanged', [$from, $to]);
        } else {
            $search =  $this->ldap->search()->users();
        }
        $paginator = $search->select($attruser)->paginate($recordsPerPage, $currentPage);


        $total =  $paginator->getPages();
        $current = $paginator->getCurrentPage();
        $paginator->getPerPage();
        $count = $paginator->count();

        $i = 0;
        $recdb = null;
        $allusers = null;
        for ($i = 0; $i < $total; $i++) {
            foreach ($paginator as $result) {
                if ($result->isEnabled()) {
                    $result['useraccountcontrol'] = 'true';
                } else {
                    $result['useraccountcontrol'] = 'false';
                }
                $rec[] = $result;

                $maprec = [];
                foreach ($mapattuser as $Key => $value) {
                    if ($Key == 'objectguid') {
                        $maprec[$value] = $result->getConvertedGuid();
                    }elseif ($Key == 'mail'){
                        $usermail = null;
                        $usermail = $result->getEmail();

                        if($usermail === null || $usermail === ''){
                            $maprec[$value] = null;
                          
                        }else{
                          
                            $maprec[$value] = "{" . preg_quote($usermail). "}";


                        }
                    }
                     elseif ($Key == 'objectsid') {
                        $maprec[$value] = $result->getConvertedSid();
                    } elseif ($Key == 'lastlogon') {
                        $lastlogon = $result['lastlogon'][0];
                        $win_time = $lastlogon;
                        $unix_time = round($win_time / 10000000) - 11644477200;
                        $lastlogon = date("Y-m-d H:i:s", $unix_time);
                        $maprec[$value] = $lastlogon;
                    } elseif ($Key == 'whencreated') {
                        $maprec[$value] = $result->getCreatedAtDate();
                    } elseif ($Key == 'whenchanged') {
                        $maprec[$value] = $result->getUpdatedAtDate();
                    } elseif ($Key == 'memberof') {
                        $maprec[$value] =  $this->getgroupbydn($result); // $result[$Key];
                    } else {
                        $maprec[$value] = is_array($result[$Key]) ? $result[$Key][0] : $result[$Key];
                        
                    }
                }
                $recdb[] = $maprec;
            }

            $allusers[] = $recdb;
            $rec = null;
            $recdb = null;
            $currentPage = $currentPage + 1;
            $paginator = $search->select($attruser)->paginate($recordsPerPage, $currentPage);
        }
        return $allusers;
    }

    public function getcomputers($usn = null, $from = null, $to = null)
    {

        $mapattcom = [
            'CN' => 'common_name',
            'samaccountname' => 'sam_account_name',
            'distinguishedname' => 'obj_dist_name',
            'lastlogon' => 'last_logon',
            'whencreated' => 'when_created',
            'whenchanged' => 'when_changed',
            'objectguid' => 'object_guid',
            'memberof' => 'memberof',
            'operatingsystem' => 'operating_system',
            'operatingsystemversion' => 'operating_system_version',
            'usnchanged' => 'usnchanged'
        ];
        $attrcom = [
            'CN',
            'samaccountname',
            'distinguishedname',
            'lastlogon',
            'usnchanged',
            'whencreated',
            'whenchanged',
            'objectguid',
            'objectsid',
            'memberof',
            'operatingsystem',
            'operatingsystemversion',


        ];
        //->select($attrgrp)
        $recordsPerPage = 100;
        $currentPage = 0;
        if ($usn == 'usn') {
          
            $search =  $this->ldap->search()->computers()->whereBetween('usnchanged', [$from, $to]);
        } else {
            $search =  $this->ldap->search()->computers();
        }
        $paginator = $search->select($attrcom)->paginate($recordsPerPage, $currentPage);

        $total =  $paginator->getPages();
        $current = $paginator->getCurrentPage();
        $paginator->getPerPage();
        $count = $paginator->count();
        $i = 0;
        $all = null;
        $rec = [];
        $recdb = null;
        for ($i = 0; $i < $total; $i++) {
            foreach ($paginator as $result) {
                $rec[] = $result;
                $maprec = [];
                foreach ($mapattcom as $Key => $value) {
                    if ($Key == 'objectguid') {
                        $maprec[$value] = $result->getConvertedGuid();
                    } elseif ($Key == 'objectsid') {
                        $maprec[$value] = $result->getConvertedSid();
                    } elseif ($Key == 'lastlogon') {
                        $lastlogon = $result['lastlogon'][0];
                        $win_time = $lastlogon;
                        $unix_time = round($win_time / 10000000) - 11644477200;
                        $lastlogon = date("Y-m-d H:i:s", $unix_time);
                        $maprec[$value] = $lastlogon;
                    } elseif ($Key == 'whencreated') {
                        $maprec[$value] = $result->getCreatedAtDate();
                    } elseif ($Key == 'whenchanged') {
                        $maprec[$value] = $result->getUpdatedAtDate();
                    } elseif ($Key == 'memberof') {
                        $maprec[$value] = $this->getgroupbydn($result);
                    } else {
                        $maprec[$value] = is_array($result[$Key]) ? $result[$Key][0] : $result[$Key];
                      
                    }
                }
                $recdb[] = $maprec;
            }

            $all[] = $recdb;

            $rec = null;
            $recdb =null;
            $currentPage = $currentPage + 1;
            $paginator = $search->select($attrcom)->paginate($recordsPerPage, $currentPage);
        }
        return $all;
    }
    public function handeldeletde($obj)
    {
       try {
           $results = $obj;
           $all= null;
           $maprec = null;
           $mapguid = null;
         
           foreach ($results as $result) {
               foreach ($result as $Key) {
                   if ($Key == 'samaccountname') {
                       if ($result['samaccountname'] != null) {
                           $mapguid[] = $result['samaccountname'];
                       }
                      
                   }
               }
           }
           $maprec['obj_dist_name'] = $this->basedn;
           $maprec['deleted_flag'] = 'true';
           $this->musers::wherein('sam_account_name',$mapguid)->update($maprec);

           $maprec['obj_dist_name'] = $this->basedn;
           $maprec['deleted_flag'] = 'true';
       
           return   ['status' => 0];
            
            
       } catch (\Exception $e) {
            return ['status' =>-2,'msg' => $e->getMessage()];
       }
    }
     public function usnchange1()
    {
        try {
            $db =  DB::connection('pgsql3');
          $u =  $db->select('select * from ad_user') ;
            $this->errors =  $db->select('select * from ad_usersf');
            $u =  $db->select('select * from ad_groups');


            return [
                'status' => 0, "success" => [$u],
                "notsync" => $this->errors,
            ];
            
        } catch (\PDOException $e) {
            $this->errors = $e->getMessage();
            return false ;

           // array_push($this->errors, '4');
            
        } catch (\Exception $e) {
            return response()->json(['status' => -2, "msg" => $e->getMessage()], 200);
        }

    }
  

    

    public function usnchange()
    {
        

        try {
            $db =  DB::connection('pgsql3');
            $sync_status = [];

            $count = AD_LdapConnection::count();
            if (!$count > 0) {
                return ['status' => -2, 'msg' => 'Can not find Ldap server configuration'];
            }


            $adconfig =  AD_LdapConnection::first();
            $config = [];
            $config['hosts'] =   [$adconfig->hosts];
            $config['base_dn'] =   $adconfig->base_dn;
            $config['username'] =   $adconfig->username;
            $config['password'] =   $adconfig->password;
            $config['account_prefix'] =   $adconfig->account_prefix;
            $config['account_suffix'] =   $adconfig->account_suffix;
            $config['port'] =   $adconfig->port;
            $config['follow_referrals'] =   $adconfig->follow_referrals;
            $config['use_ssl'] =   $adconfig->use_ssl;
            $config['use_tls'] =   $adconfig->use_tls;
            $config['version'] =  3;
            $config['timeout'] =   $adconfig->timeout;
            $this->basedn =   $adconfig->base_dn;

            $ldapconection =   ADTreatMapDB::ldapconect($config);
            if (is_array($ldapconection)) {
                if ($ldapconection['status'] == 0) {
                    $this->ldap = $ldapconection['ldap'];
                }
                if ($ldapconection['status'] != 0) {
                    return  $ldapconection;
                }
            } else {
                return  $ldapconection;
            }

   
        $rootDse =  $this->ldap->search()->getRootDse();

        $highest = ['highest_committed_usn' => $rootDse['highestcommittedusn'][0]];
        $model = $this->mvaluestore;
        $newvalues = $model::where('highest_committed_usn', '>=', '0')->first();
        
        $fromusn = $newvalues['highest_committed_usn'];
        $tousn =  $rootDse['highestcommittedusn'][0];

      

            // $sync_status[] = ['rootDse' => [$rootDse]];

            $allusers = $this->getusers('usn', $fromusn, $tousn);

            $ou = $this->getous('usn', $fromusn, $tousn);

            
            $allgrp = $this->getgroups('usn', $fromusn, $tousn);

            $computers = $this->getcomputers('usn', $fromusn, $tousn);

            $rootDse =  $this->ldap->search()->getRootDse();

            // return [$ou, $allgrp, $computers,$allusers,$fromusn,$tousn];
            //sync ou


            $pk = 'object_guid';
            $runssync = ADTreatMapDB::mstore($ou, $this->mous, $pk);
            if (is_array($runssync)) {
                if ($runssync['status'] != 0) {
                    return  $runssync;
                }
            } else {
                return  $runssync;
            }
            $sync_status[] = ['sync_ous' => 'success'];

            //sync groups
            $pk = 'object_guid';
            $runssync = ADTreatMapDB::mstore(
                $allgrp,
                $this->mgrps,
                $pk
            );
            if (is_array($runssync)) {
                if ($runssync['status'] != 0) {
                    return  $runssync;
                }
            } else {
                return  $runssync;
            }
            $sync_status[] = ['sync_groups' => 'success'];


            //sync users
            $pk = 'object_guid';
            $runssync = ADTreatMapDB::mstore($allusers, $this->musers, $pk);
            if (is_array($runssync)) {
                if ($runssync['status'] != 0) {
                    return  $runssync;
                }
            } else {
                return  $runssync;
            }
            $sync_status[] = ['sync_users' => 'success'];



            //sync computers
            $pk = 'object_guid';
            $runssync = ADTreatMapDB::mstore($computers, $this->mcomputer, $pk);
            if (is_array($runssync)) {
                if ($runssync['status'] != 0) {
                    return  $runssync;
                }
            } else {
                return  $runssync;
            }
            $sync_status[] = ['sync_computers' => 'success'];

            //sync groups in group

            //sync groups in group
            $op_grp_ou = false;
            if (is_array($allgrp)) {
            $grpstatus =  $this->objinboj($allgrp, $this->mgrpingrp, $op_grp_ou);

            if ($grpstatus !== true) {
                if (is_array($grpstatus)) {
                    if ($grpstatus['status'] != 0) {
                        return  $grpstatus;
                    }
                } else {
                    return  $grpstatus;
                }
            }
          }
           
            $sync_status[] = ['sync_groups_in_groups' => 'success'];

            //sync users in group  
            if (is_array($allusers)) {
                $userstatus =  $this->objinbojbychild($allusers, $this->musersingrp, $op_grp_ou, 'user');
                if ($userstatus !== true) {
                    if (is_array($userstatus)) {
                        if ($userstatus['status'] != 0) {
                            return  $userstatus;
                        }
                    } else {
                        return  $userstatus;
                    }
                }
            }
            $sync_status[] = ['sync_users_in_groups' => 'success'];
              
            // sync computer in groups mcompuersingrp
            if (is_array($computers)) {
                $op_grp_ou = false;
                $comstatus =  $this->objinbojbychild($computers, $this->mcompuersingrp, $op_grp_ou, 'pc');
                if ($comstatus !== true) {
                    if (is_array($comstatus)) {
                        if ($comstatus['status'] != 0) {
                            return  $comstatus;
                        }
                    } else {
                        return  $comstatus;
                    }
                }
            }
            $sync_status[] = ['sync_computers_in_groups' => 'success'];


            //$rootDse['highestcommittedusn'][0]

            $pk = 'highest_committed_usn';
            $highest = ['highest_committed_usn' => $tousn];
            $model = $this->mvaluestore;
            $newvalues = $model::where('highest_committed_usn', '>=', '0')->first();
            if ($newvalues !== null) {

                $newvalues->update($highest);
            } 

            $sync_status[] = $highest;


            return [
                'status' => 0,
                "success" => 'active directory sync',
                "From_change" => $fromusn,
                "To_change" => $tousn,
                "sync_status" => $sync_status
            ];
        } catch (\Exception $e) {
            $results =  ['status' => -2, 'msg' => $e->getMessage(), "sync_status" => $sync_status];

            return $results;

            // something went wrong
        }
        // return [$allusers, $all,$ou,$computers, $rootDse['highestcommittedusn']];
    }


}
