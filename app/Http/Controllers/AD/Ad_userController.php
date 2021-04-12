<?php

namespace App\Http\Controllers\AD;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\AD\Ad_user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;


class Ad_userController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store', 'multipleAdd']]);
        $this->middleware('role:update', ['only' => ['update', 'multipleUpdate']]);
        $this->middleware('role:delete', ['only' => ['destroy']]);
    }

    private $m = Ad_user::class;

    private $pk = 'object_guid';

    public function index()
    {
        return Ad_user::orderBy('object_guid', 'asc')
               ->get();
    }

    public function show($object_guid)
    {
        $User_nameInfo = Ad_user::where('object_guid', $object_guid)
            ->with('groupsname', 'groupsname.grpname')
            ->first();
        if ($User_nameInfo == null) {
            return [];
        } 
        return $User_nameInfo;
    }

    public function multipleAdd(Request $request)
    {

        // $jsonarray = json_decode(json_encode($$request->all()), TRUE); // $b=your json array
        $datajson =   json_decode(json_encode($request->all()), TRUE);
        $length = count($datajson['data']);
        $dataobj = $datajson['data'];
        $formatmap = $datajson['format'];
        $lengthformat = count($datajson['format']);

        $results = [];

        $db = DB::connection('pgsql2');
        $db->beginTransaction();
        //DB::beginTransaction();

        $errorflg = 0;
        $formatkeys = [];

        try {


            for ($i = 0; $i < $length; $i++) {
                $row = [];
                for ($c = 0; $c < $lengthformat; $c++) {
                    $row[$formatmap[$c]] =  $dataobj[$i][$c];
                }


                $datetimestamp = $row['time'];

                $now = DateTime::createFromFormat('U.u', $datetimestamp);

                $data['start_time'] =   $now->format("m-d-Y H:i:s.u");
                $row['time'] = $data['start_time'];
                $code_status = $row['code_status'];

                $cache_hit_code = strtok($code_status, '/');
                $response_code = substr($code_status, strpos($code_status, "/") + 1);

                $row['cache_hit_code'] = $cache_hit_code;
                $row['response_code'] = $response_code;


                unset($row['code_status']);


                $peerstatus_peerhost = $row['peerstatus_peerhost'];

                $peerstatus = strtok($peerstatus_peerhost, '/');
                $peerhost = substr($peerstatus_peerhost, strpos($peerstatus_peerhost, "/") + 1);

                $row['peerstatus'] = $peerstatus;
                $row['peerhost'] = $peerhost;


                unset($row['peerstatus_peerhost']);

                $results =  $this->rmStore($this->m, $row, $this->pk);
            }
        } catch (\Exception $e) {
            $results =  ['status' => 1, 'error' => $e->getMessage()];
            $errorflg = 1;
            $db->rollback();
            return $results;

            // something went wrong
        } catch (\Throwable $e) {
            $results =  ['status' => 1, 'error' =>  $e->getMessage()];
            $errorflg = 1;
            $db->rollback();
            return $results;
        }
        if ($errorflg == 0) {
            $db->commit();
        } else {

            $results =  ['status' => 1, 'error' =>  'some thing wrong'];
            $db->rollback();
        }




        return  ['status' => 0];


        //  $items = $request->all();

        //Ad_user::insert($request);
        // return 'suucses';
    }
    public function destroy($prefix, Ad_user $model)
    {
        $model->destroy($prefix);

        return ['status' => 0];
        //   return $this->rDestroy($model);
    }
    public function store(Request $request)
    {
        return $this->rStore($this->m, $request, $this->pk);
    }

    public function search(Request $request)
    {
        $columns =
            [
            'object_guid',
            'display_name',
            'surname',
            'given_name',
            'sam_account_name',
            'sam_account_name',
            'physical_delivery_office_name',
            'phone_number',
            'email',
            'department',
            'obj_dist_name',
            'last_logon',
            'logon_count',
            'user_principal_name',
            'is_enabled',
            'when_created',
            'when_changed',
            ];;

        $model = Ad_user
            ::orderBy('object_guid', 'asc');

        return ModelTreatment::getAsyncData($model, $request, $columns, 'ad', 'ad_user', 'object_guid', 'ASC');
    }
    public function itemsList($mode, $search = '')
    {
        $ad_user = DB::table('ad_user')
            ->select('object_guid', DB::raw("CONCAT(object_guid, ' ', domin) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'object_guid') {
            $ad_user = $ad_user->where('object_guid', $search);
        } else if ($mode == 'phrase') {
            $ad_user = $ad_user->where(DB::raw("CONCAT(object_guid, ' ', domin)"), 'like', '%' . $search . '%');
        }
        return $ad_user->take(100)->get();
    }

    // User List Page
    public function users()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/ad-data/users/index', ['pageConfigs' => $pageConfigs]);
    }

    // Tree View Page
    public function tree_view()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/ad-data/tree-view/index', ['pageConfigs' => $pageConfigs]);
    }

    // Groups List Page
    public function groups()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/ad-data/groups/index', ['pageConfigs' => $pageConfigs]);
    }

    // Organizational units List Page
    public function containers()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/ad-data/containers/index', ['pageConfigs' => $pageConfigs]);
    }

    // Organizational units List Page
    public function organizational_units()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/ad-data/organizational-units/index', ['pageConfigs' => $pageConfigs]);
    }

    // User List
    public function user_list(Request $request)
    {
        $common_name    = $request->common_name;
        $surname        = $request->surname;
        $email          = $request->email;
        $user = DB::table('ad_user')->orderBy('when_created','desc');
        if($common_name != ''){
            $user->where('common_name','LIKE','%'.$common_name.'%');
        }
        if($surname != ''){
            $user->where('surname','LIKE','%'.$surname.'%');
        }
        if($email != ''){
            $user->where('email_addresses','LIKE','%'.$email.'%');
        }
        $user = $user->paginate(10);
        return $user;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showUser(User $user)
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/ad-data/users/show-ad-data-user', ['pageConfigs' => $pageConfigs], compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showSubnet(User $user)
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/ad-data/subnet/show-ad-data-subnet', ['pageConfigs' => $pageConfigs], compact('user'));
    }
}
