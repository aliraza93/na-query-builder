<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin\User;

class AdDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    // User List Page
    public function users()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/ad-data/users/index', ['pageConfigs' => $pageConfigs]);
    }

    //Computer List Page
    public function computers() {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/ad-data/computers/index', ['pageConfigs' => $pageConfigs]);
    }

    // User List Page
    public function subnet()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/ad-data/subnet/index', ['pageConfigs' => $pageConfigs]);
    }

    // Tree View Page
    public function tree_view()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/ad-data/tree-view/index', ['pageConfigs' => $pageConfigs]);
    }

    // Groups List Page
    public function groups()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/ad-data/groups/index', ['pageConfigs' => $pageConfigs]);
    }

    // Organizational units List Page
    public function containers()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/ad-data/containers/index', ['pageConfigs' => $pageConfigs]);
    }

    // Organizational units List Page
    public function organizational_units()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/ad-data/organizational-units/index', ['pageConfigs' => $pageConfigs]);
    }

    // User List
    public function user_list(Request $request)
    {
        $name = $request->name;
        $user = User::orderBy('when_created','desc');
        if($name != ''){
            $user->where('name','LIKE','%'.$name.'%');
        }
        $user = $user->paginate(10);
        return $user;
    }

    // Computer List
    public function computer_list(Request $request)
    {
        $name = $request->name;
        $user = User::orderBy('when_created','desc');
        if($name != ''){
            $user->where('name','LIKE','%'.$name.'%');
        }
        $user = $user->paginate(10);
        return $user;
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showUser(User $user)
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/ad-data/users/show-ad-data-user', ['pageConfigs' => $pageConfigs], compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showComputer(User $user)
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/ad-data/computers/show-ad-data-computer', ['pageConfigs' => $pageConfigs], compact('user'));
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
