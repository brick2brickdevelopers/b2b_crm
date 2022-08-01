<?php

namespace App\Http\Controllers\Member;

use App\CallPurpose;
use App\Campaign;
use App\DataTables\Member\CallReportDataTable;
use App\EmployeeDetails;
use Illuminate\Http\Request;

class CallRecordController extends MemberBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageIcon = 'icon-list';
        $this->pageTitle = 'Call Records';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CallReportDataTable $dataTable, Request $request)
    {
        $this->campaigns = Campaign::all();
        $this->employees = EmployeeDetails::all();
        $this->call_purpose = CallPurpose::all();
        return $dataTable->render('member.call-records.index', $this->data);
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
