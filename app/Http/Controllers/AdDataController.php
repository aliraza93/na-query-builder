<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin\User;
use App\Models\AD\AD_Users;

class AdDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function subnet()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/ad-data/subnet/index', ['pageConfigs' => $pageConfigs]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function tree_view()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/ad-data/tree-view/index', ['pageConfigs' => $pageConfigs]);
    }

    // Subnet List
    public function subnet_list(Request $request)
    {
        $name = $request->name;
        $user = User::orderBy('when_created','desc');
        if($name != ''){
            $user->where('name','LIKE','%'.$name.'%');
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
    public function showSubnet(User $user)
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/ad-data/subnet/show-ad-data-subnet', ['pageConfigs' => $pageConfigs], compact('user'));
    }
}
