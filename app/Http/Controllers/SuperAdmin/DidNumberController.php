<?php

namespace App\Http\Controllers\SuperAdmin;

use App\DidNumber;
use App\Helper\Reply;
use Illuminate\Http\Request;

class DidNumberController extends SuperAdminBaseController
{
     /**
     * SuperAdminContactNumberController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'DID Numbers';
        $this->pageIcon = 'icon-settings';
    } 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->didNumbers = DidNumber::all();
        return view('super-admin.did-managment.did-number', $this->data);
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
      $didNumber = new DidNumber();
      $didNumber->number = $request->number;
     
      $didNumber->save();
      return back()->with('success','Did Number created successfully!');

    
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
          $didNumber =  DidNumber::find($id);
          $didNumber->number = $request->number;
          $didNumber->save();

          return back()->with('success','Did Number Updated successfully!');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $didNumber =  DidNumber::find($id);

        $didNumber->delete();

        return back()->with('success','Did Number Deleted successfully!');

    }
}
