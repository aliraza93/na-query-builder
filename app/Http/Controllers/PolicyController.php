<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin\User;

class PolicyController extends Controller
{

    // Policy List Page
    public function policies()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/policy/policies/index', ['pageConfigs' => $pageConfigs]);
    }

    // Reports List Page
    public function reports()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/policy/reports/index', ['pageConfigs' => $pageConfigs]);
    }

    // Reports List Page
    public function rules()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/policy/rules/index', ['pageConfigs' => $pageConfigs]);
    }

    // URL List Page
    public function url_list()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/policy/url-list/index', ['pageConfigs' => $pageConfigs]);
    }

    // Block Pages List Page
    public function block_pages()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/policy/block-pages/index', ['pageConfigs' => $pageConfigs]);
    }

    // Settings List Page
    public function settings()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/policy/settings/index', ['pageConfigs' => $pageConfigs]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showPolicy(User $user)
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/policy/policies/show-policy', ['pageConfigs' => $pageConfigs], compact('user'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rule_builder()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('content.policy.rules.rule-builder', ['pageConfigs' => $pageConfigs]);
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
