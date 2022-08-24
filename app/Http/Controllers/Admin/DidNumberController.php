<?php

namespace App\Http\Controllers\Admin;

use App\DidNumber;
use App\Helper\Reply;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DidNumberController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageIcon = 'icon-phone';
        $this->pageTitle = 'Did Numbers';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->did_numbers = DidNumber::where('company_id',company()->id)->get();
        return view('admin.did-number.index', $this->data);
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

    public function isDefault(Request $request)
    {
        $checkDefault = DidNumber::where('is_default', true)->first();
        if ($checkDefault) {
            if ($checkDefault->id == $request->id) {
                $did_number = DidNumber::find($request->id);
                $did_number->is_default = false;
                $did_number->save();
                return Reply::redirect(route('admin.did-number.index'), ('Did number removed from deafult'));
            } else {
                return Reply::redirectWithError(route('admin.did-number.index'), 'Another Did Number is Already deafult');
            }
        } else {
            $did_number = DidNumber::find($request->id);
            if ($did_number->is_default == true) {
                $did_number->is_default = false;
            } else {
                $did_number->is_default = true;
            }
            $did_number->save();
            return Reply::redirect(route('admin.did-number.index'), ('Did Number deafult Successfully.'));
        }
    }

}
