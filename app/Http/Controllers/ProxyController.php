<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProxyController extends Controller
{
    // Listeners List Page
    public function listeners()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/proxy/listeners/index', ['pageConfigs' => $pageConfigs]);
    }

    // CA List Page
    public function ca()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/proxy/CA/index', ['pageConfigs' => $pageConfigs]);
    }

    // Generate CA List Page
    public function GenerateCA()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/proxy/GenerateCA/index', ['pageConfigs' => $pageConfigs]);
    }

    // Upload CA List Page
    public function upload_ca_page()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/proxy/UploadCA/index', ['pageConfigs' => $pageConfigs]);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function upload_ca(Request $request)
    {
        dd($request->all());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
