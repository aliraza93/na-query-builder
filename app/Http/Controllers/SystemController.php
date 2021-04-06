<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SystemController extends Controller
{
    // System Maintenance Page
    public function maintenance()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/system/maintenance/index', ['pageConfigs' => $pageConfigs]);
    }
    
    // System Logs Page
    public function logs()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/system/logs/index', ['pageConfigs' => $pageConfigs]);
    }

    // System Clock Page
    public function system_clocks()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/system/clocks/index', ['pageConfigs' => $pageConfigs]);
    }

    //LDAP Configurations
    public function ldap_configurations() {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/system/ldap-configurations/index', ['pageConfigs' => $pageConfigs]);
    }
}
