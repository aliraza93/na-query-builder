<?php

namespace App\Http\Controllers;
use App\Models\Operators;
use Illuminate\Http\Request;

class OperatorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function operators_list(Request $request)
    {
        $type       = $request->type;
        $optGroup   = $request->optGroup;
        
        $operators  = Operators::orderBy('when_created', 'desc'); 
        if($type) {
            $operators->where('type', 'LIKE', '%' . $type . '%');
        }
        if($optGroup) {
            $operators->where('optGroup', 'LIKE', '%' . $optGroup . '%');
        }

        $operators = $operators->paginate(10);
        return $operators;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request->all());
    }
}
