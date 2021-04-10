<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QueryBuilderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function operators()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('content.query-builder.operators.index', ['pageConfigs' => $pageConfigs]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function triggers()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('content.query-builder.triggers.index', ['pageConfigs' => $pageConfigs]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function operands()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('content.query-builder.operands.index', ['pageConfigs' => $pageConfigs]);
    }
}
