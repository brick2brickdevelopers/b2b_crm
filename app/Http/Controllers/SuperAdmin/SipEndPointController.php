<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Helper\Reply;
use App\Http\Controllers\Controller;
use App\SipEndPoint;
use Illuminate\Http\Request;

class SipEndPointController extends SuperAdminBaseController
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->sipEndPoints = SipEndPoint::all();
        return view('super-admin.sip-end-point.create',$this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $group = new SipEndPoint();
        $group->name = $request->name;
        $group->save();

        $designations = SipEndPoint::all();
        $teamData = '';

        foreach ($designations as $team){
            $selected = '';

            if($team->id == $group->id){
                $selected = 'selected';
            }

            $teamData .= '<option '.$selected.' value="'.$team->id.'"> '.$team->name.' </option>';
        }

        return Reply::successWithData('Group created successfully.', ['designationData' => $teamData]);
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
