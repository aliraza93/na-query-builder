<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NetworkController extends Controller
{
    // Network Interface Page
    public function interface()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/network/interface/index', ['pageConfigs' => $pageConfigs]);
    }

    // Network Firewall Page
    public function firewall()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/network/firewall/index', ['pageConfigs' => $pageConfigs]);
    }
}
