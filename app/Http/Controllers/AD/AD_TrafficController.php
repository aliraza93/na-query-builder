<?php

namespace App\Http\Controllers\AD;

use App\Http\Controllers\Controller;
use App\Models\AD\AD_Traffic;
use App\Models\Libraries\ModelTreatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AD_TrafficController extends Controller
{
  public function __construct()
  {
    $this->middleware('role:read', ['only' => ['index', 'search']]);
  }

  public function index()
  {
    return AD_Traffic
      ::orderBy('when_created', 'desc')
        ->get();
  }

  public function search(Request $request)
  {
    $columns = [
      'source_ip',
      'referrer_url',
      'subtype_traffic_link',
      'vts_tl.common_name as ts_name_traffic_link',
      'subtype_policy',
      'vts_p.common_name as ts_name_policy',
      'rule_name',
      'rbc.choice_label as match_action',
      'policy_name',
      'inheritance_display',
      'request_url',
      'content_length',
      'content_type',
      'block_reason',
      'block_condition',
      'match_action',
      'u.common_name as ad_user',
      'c.common_name as ad_computer',
      'content_category_response_time_ms',
      'classify_matched',
      'content_category_top5',
      'tl.when_created'
    ];
    try {
      $model = DB::table('traffic_log as tl')
        ->select($columns)
        ->leftJoin('v_traffic_source as vts_tl', 'tl.ts_id_traffic_link', '=', 'vts_tl.ts_id')
        ->leftJoin('v_traffic_source as vts_p', 'tl.ts_id_policy', '=', 'vts_p.ts_id')
        ->leftJoin('ad_user as u', 'tl.object_guid_user', '=', 'u.object_guid')
        ->leftJoin('ad_user as c', 'tl.object_guid_computer', '=', 'c.object_guid')
        ->leftJoin('rule_builder_choice as rbc', function ($join) {
          $join->on('tl.match_action', '=', 'rbc.choice_label')
            ->where('rbc.input_code', 'ACTION');
        });

      return ModelTreatment::getAsyncData($model, $request, $columns, 'ad', 'traffic_log', 'when_created', 'desc');
    }catch (\Exception $e) {

      return $e->getMessage();
    }
  }
  
  public function itemsList($mode, $search = '')
  {
    $AD_Traffic = DB::table('traffic_log')
      ->select('audit_id', DB::raw("when_created as phrase"))
      ->orderBy("phrase", 'asc');

    if ($mode == 'audit_id') {
      $AD_Traffic = $AD_Traffic->where('audit_id', $search);
    } else if ($mode == 'phrase') {
      $AD_Traffic = $AD_Traffic->where(DB::raw("when_created"), 'like', '%' . $search . '%');
    }
    return $AD_Traffic->take(100)->get();
  }
}
