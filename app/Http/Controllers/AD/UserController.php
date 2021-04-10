<?php

namespace App\Http\Controllers\AD;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Adldap\Laravel\Facades\Adldap;

use Adldap\AdldapInterface;

class UserController extends Controller
{
    /**
     * @var Adldap
     */
    protected $ldap;

    /**
     * Constructor.
     *
     * @param AdldapInterface $adldap
     */
    public function __construct(AdldapInterface $ldap)
    {
        $this->ldap = $ldap;
    }

    /**
     * Displays the all LDAP users.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = $this->ldap->search()->users()->get();
        

        return response()->json($users);
        //return view('users.index', compact('users'));
    }

    /**
     * Displays the specified LDAP user.
     *
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        //https://github.com/Adldap2/Adldap2/blob/master/docs/models/user.md
        //https://adldap2.github.io/Adldap2/#/setup?id=getting-started
         //ab6792ea-edf6-4826-a749-e27a7fb928ee


        $user = $this->ldap->search()->findByGuid($request->get('UserId'));
    
        $grp =  $this->ldap->search()->findByGuid($request->get('UserId'))->getGroups();

        $name = $this->ldap->search()->findByGuid($request->get('UserId'))->getCommonName();

        $conv = $this->ldap->search()->findByGuid($request->get('UserId'))->getConvertedSid();

        $emai = $this->ldap->search()->findByGuid($request->get('UserId'))->getEmail();

        $computer = $this->ldap->search()->computers()->first();

        return response()->json([$user, $grp, $name, $conv, $provider, $computer]);

     //   return response()->json($user);
    }
}