<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Libraries\ModelTreatment;
use App\Models\Crm\Squidlog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;


class SquidLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('role:insert', ['only' => ['store', 'multipleAdd']]);
        $this->middleware('role:update', ['only' => ['update', 'multipleUpdate']]);
        $this->middleware('role:delete', ['only' => ['destroy']]);
    }

    private $m = Squidlog::class;

    private $pk = 'id';

    public function index()
    {
        return Squidlog
            ::orderBy('id', 'asc')
            ->get();
    }

    public function show($id)
    {
        $User_nameInfo = Squidlog
            ::where('id', $id)
            ->first();
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

        //Squidlog::insert($request);
        // return 'suucses';
    }
    public function store(Request $request)
    {
        //date('Y-m-d H:i:s:u');
        $datetimestamp = $request['time'];

        $now = DateTime::createFromFormat('U.u', $datetimestamp);

        $data['time'] =   $now->format("m-d-Y H:i:s.u");
        $request->merge($data);
        return $this->rStore($this->m, $request, $this->pk);
    }

    public function search(Request $request)
    {
        $columns =
            [
                'time',
                'elapsed',
                'remotehost',
                'response_code',
                'cache_hit_code',
                'bytes',
                'method',

                'url',
                'rfc931',
                'type',
                'peerstatus',
                'peerhost',

            ];;

        $model = Squidlog
            ::orderBy('id', 'asc');

        return ModelTreatment::getAsyncData($model, $request, $columns, 'crm', 'squid_log', 'id', 'ASC');
    }
    public function itemsList($mode, $search = '')
    {
        $squid_log = DB::table('squid_log')
            ->select('id', DB::raw("CONCAT(id, ' ', domin) as phrase"))
            ->orderBy("phrase", 'asc');

        if ($mode == 'id') {
            $squid_log = $squid_log->where('id', $search);
        } else if ($mode == 'phrase') {
            $squid_log = $squid_log->where(DB::raw("CONCAT(id, ' ', domin)"), 'like', '%' . $search . '%');
        }
        return $squid_log->take(100)->get();
    }
}
