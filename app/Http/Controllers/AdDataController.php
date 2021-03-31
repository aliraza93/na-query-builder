<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

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
        return view('/content/ad-data/users/ad-data-users', ['pageConfigs' => $pageConfigs]);
    }

    // User List
    public function user_list()
    {
        // $date = $request->date;
        // $email = $request->email;
        $user = User::orderBy('created_at','desc');
        // if($date != ''){
        //     $user->where('date','LIKE','%'.$date.'%');
        // }
        // if($email != ''){       
        //     $user->where('email','LIKE','%'.$email.'%');
        // }
        $user = $user->paginate(5);
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
