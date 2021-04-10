<?php

namespace App\Http\Controllers\AD;

use App\Http\Controllers\Controller;
use App\Models\AD\AD_LogPreserve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AD_LogPreserveController extends Controller
{
  public function __construct()
  {
    $this->middleware('role:read', ['only' => ['index']]);
    $this->middleware('role:insert', ['only' => ['updateLog']]);
    $this->middleware('role:update', ['only' => ['updateLog']]);
  }

  private $m = AD_LogPreserve::class;

  public function index()
  {
    return AD_LogPreserve
      ::first();
  }

  public function updateLog(Request $request)
  {
    request()->validate([
      'log_preserve_days_allowed' => 'required|integer',
      'log_preserve_days_rejected' => 'required|integer',
    ]);

    $allowed = $request['log_preserve_days_allowed'];
    $rejected = $request['log_preserve_days_rejected'];

    if ($this->m::count() === 1) {
      DB::connection('pgsql3')->table('configuration')->update(['log_preserve_days_allowed' => $allowed, 'log_preserve_days_rejected' => $rejected]);
    } else {
      DB::connection('pgsql3')->insert('insert into configuration (log_preserve_days_allowed, log_preserve_days_rejected) values (?, ?)', [$allowed, $rejected]);
    }

    return response()->json('Saved Successfully', 200);
  }
}
