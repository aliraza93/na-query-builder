<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin\User;

class PolicyController extends Controller
{

    // Reports List Page
    public function reports()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/policy/reports/index', ['pageConfigs' => $pageConfigs]);
    }

    // URL List Page
    public function url_list()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/policy/url-list/index', ['pageConfigs' => $pageConfigs]);
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
}
