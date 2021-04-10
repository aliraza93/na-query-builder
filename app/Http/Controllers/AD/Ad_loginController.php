<?php

namespace App\Http\Controllers\AD;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\AD\Ad_login;
use App\Models\AD\Ad_user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;
use Adldap\Laravel\Facades\Adldap;
use Adldap\AdldapInterface;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Libraries\ADTreatMapDB;


class Ad_loginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * @var Adldap
     */
    protected $ldap;

    /**
     * Constructor.
     *
     * @param AdldapInterface $adldap
     */
    public function __construct(AdldapInterface $ldap)
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store', 'multipleAdd']]);
        $this->middleware('role:update', ['only' => ['update', 'multipleUpdate']]);
        $this->middleware('role:delete', ['only' => ['destroy']]);
        $this->ldap = $ldap;
    }
    

    private $m = Ad_login::class;

    private $pk = 'ip_address';

    public function index()
    {

        return Ad_login
            ::orderBy('ad_observed_ip', 'asc')
            ->get();
    }

    public function show($UserId)
    {
        $User_nameInfo = Ad_login
            ::where('ad_observed_ip', $UserId)
            ->first();
        return $User_nameInfo;
    }

  
    public function destroy($prefix, Ad_login $model)
    {

        $model->destroy($prefix);


        return ['status' => 0];
        //   return $this->rDestroy($model);
    }
    public function store(Request $request)
    {
         
  /*
{
  "Timestamp": "2020-09-28T20:43:00.7576126+05:00",
  "IsComputerEvent": false,
  "IPAddress": "192.168.8.21",
  "ObjectId": "CorpAD\user3",
  "ObjectName": "user3",
  "ObjectDomain": "CorpAD",
  "ObjectGuid": "84cd54a5-d42e-49a3-8327-0537a2833251",
  "DomainController": "DC1"
}

  */
        $fillable = [
            'ip_address',
            'timestamp_last_determined',
            'object_guid',
            'is_computer_event',
            'domain_controller',
            'user_id',
            'user_name',
            'user_domain',

        ];
        try {
            $map = [];
            $map['ip_address'] = $request->get('IPAddress');
            $map['object_guid'] = $request->get('ObjectGuid');
            $map['is_computer_event'] = $request->get('IsComputerEvent');
            $map['user_id'] = $request->get('ObjectId');
            $map['user_name'] = $request->get('ObjectName');
            $map['user_domain'] = $request->get('ObjectDomain');
            $map['domain_controller'] = $request->get('DomainController');
            $map['timestamp_last_determined'] = $request->get('Timestamp');
            $date = date_create($map['timestamp_last_determined']);
            $map['timestamp_last_determined'] =   date_format($date, "Y-m-d H:i:s P");

            $db = DB::connection('pgsql3');
            $ip_exist =   $db->table('ad_observed_ip')
            ->where('ip_address', '=', $map['ip_address'])
            ->where('is_computer_event', '=', $map['is_computer_event'])
            ->count();
            if ($ip_exist > 0) {
                $db->table('ad_observed_ip')
                ->where('ip_address','=',$map['ip_address'])
                 ->where('is_computer_event', '=', $map['is_computer_event'])
                ->update($map);
            } else {
                $db->table('ad_observed_ip')->insert(
                    $map
                );
            }


            return ['status' => 0];
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 200);
        }

      
      
     

    }

    public function search(Request $request)
    {
        $columns = [
            'ip_address',
            'timestamp_last_determined',
            'object_guid_user',
            'object_guid_computer',


        ];

        $model = Ad_login
            ::orderBy('ip_address', 'asc');

        return ModelTreatment::getAsyncData($model, $request, $columns, 'ad', 'ad_observed_ip', 'ip_address', 'ASC');
        
    }
    
 
    public function authlogin(Request $request)
    {
        
        try {
            $name = $request->get('UserName');
            //@egirna.local
            $username = $name.'@egirna.local';
            $password = $request->get('Password');
            $ip_address = $request->get('IP_Address');
            if (Adldap::getProvider('default')->auth()->attempt($username, $password,$bindAsUser = true)) {

               


                // $user = $this->ldap->user()->get();
                $user = Adldap::getProvider('default')->search()->users()->find($name);
                $guid = $user->getConvertedGuid();
                //  $users = $this->ldap->search()->users()->get();
                $ad_user = Ad_user::where('object_guid', '=', $guid)->first();
                    //lastlogontimestamp
                $lastlogon = $user['lastlogon'][0];
                $win_time = $lastlogon;
                $unix_time = round($win_time / 10000000) - 11644477200;
                $lastlogon = date("Y-m-d H:i:s", $unix_time);
               // $lastlogon = date("Y-m-d H:i:sP", $unix_time) ; with time zone
             
                $exit = 'exist';
                if ($ad_user === null) {
                    // user doesn't exist
                    $exit = 'user does not exist';
                    $model = Ad_user::class;
                    $fields = $model::$validator;
                    
                    $request = ADTreatMapDB::mapuser($user);
                    $insert = [];
                    foreach ($fields as $key => $value) {
                        if (array_key_exists($key, $request)) {
                            $insert[$key] = $request[$key];
                        }
                    }
                   

                    $result = $model::create($insert);




                    // DB::table('squid_log')->insert($insert);

                    $db = DB::connection('pgsql3');
                    $ip_exist =   $db->table('ad_observed_ip')->where('ip_address', '=', $ip_address)->count();
                    if ($ip_exist > 0) {
                        $db->table('ad_observed_ip')->where('ip_address', '=', $ip_address)->update(
                            [
                                'ip_address' => $ip_address,
                                'timestamp_last_determined' => $lastlogon,
                                'object_guid_user' => $guid,

                            ]
                        );
                        
                    } else {
                        $db->table('ad_observed_ip')->insert(
                            [
                                'ip_address' => $ip_address,
                                'timestamp_last_determined' => $lastlogon,
                                'object_guid_user' => $guid,

                            ]
                        );
                    }


                    return ['status' => 0];

 
                 }else{
                    $model = Ad_user::class;
                    $fields = $model::$validator;
                    $request = ADTreatMapDB::mapuser($user);
                    $update = [];
                    foreach ($fields as $key => $value) {
                        if (array_key_exists($key, $request)) {
                            $update[$key] = $request[$key];
                        }
                    }


                    $result = $model::where('object_guid', $guid)->update($update);
                    
                    $db = DB::connection('pgsql3');
                  $ip_exist =   $db->table('ad_observed_ip')->where('ip_address','=',$ip_address)->count();
                   if($ip_exist > 0){
                        $db->table('ad_observed_ip')->where('ip_address', '=', $ip_address)->update(
                            [
                                'ip_address' => $ip_address,
                                'timestamp_last_determined' => $lastlogon,
                                'object_guid_user' => $guid,

                            ]
                        );
                   }else{
                        $db->table('ad_observed_ip')->insert(
                            [
                                'ip_address' => $ip_address,
                                'timestamp_last_determined' => $lastlogon,
                                'object_guid_user' => $guid,

                            ]
                        );

                   }

        
                  


                 }
              
              
                return response()->json([$user, $guid]);

                
            } else {
                return 'invalid credentials ';
            }
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 200);
        }
    }
    


    public function itemsList($mode, $search = '')
    {
        $ad_login = DB::table('ad_login')
            ->select('UserId', DB::raw("CONCAT(UserId, ' ', UserName) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'UserId') {
            $ad_login = $ad_login->where('UserId', $search);
        } else if ($mode == 'phrase') {
            $ad_login = $ad_login->where(DB::raw("CONCAT(UserId, ' ', UserName)"), 'like', '%' . $search . '%');
        }
        return $ad_login->take(100)->get();
    }
}
