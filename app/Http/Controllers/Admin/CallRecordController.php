<?php

namespace App\Http\Controllers\Admin;

use App\CallPurpose;
use App\Campaign;
use App\EmployeeDetails;
use App\Http\Controllers\Controller;
use App\ManualLoggedCall;
use App\Team;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class CallRecordController extends AdminBaseController
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
    public function index(Builder $builder, Request $request)
    {
        $this->campaigns = Campaign::all();
        $this->employees = EmployeeDetails::all();
        $this->call_purpose = CallPurpose::all();
        if ($request->ajax()) {
            return DataTables::of(ManualLoggedCall::query()->with(['purpose']))
                ->editColumn('purpose', function ($row) {
                    if ($row->purpose) {
                        return $row->purpose->purpose;
                    } else {
                        return "N/A";
                    }
                })
                ->editColumn('call_type', function ($row) {
                    if ($row->call_type == 0) {
                        return "Manual";
                    } else {
                        return "AUTO";
                    }
                })
                ->editColumn('call_source', function ($row) {
                    if ($row->call_type == 1) {
                        return "Incomming";
                    } else {
                        return "Outgoing";
                    }
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y h:m:s A');
                })
                ->toJson();
        }
        $this->dataTable = $builder->columns([
            ['data' => 'id', 'name' => 'id', 'title' => 'Id'],
            ['data' => 'lead_number', 'name' => 'lead_number', 'title' => 'Lead Number'],
            ['data' => 'agent_number', 'name' => 'agent_number', 'title' => 'Agent Number'],
            ['data' => 'did', 'name' => 'name', 'title' => 'DID Number'],
            ['data' => 'id', 'name' => 'name', 'title' => 'Call Status'],
            ['data' => 'purpose', 'name' => 'purpose', 'title' => 'Call purpose'],
            ['data' => 'call_type', 'name' => 'call_type', 'title' => 'Call Type'],
            ['data' => 'call_source', 'name' => 'call_source', 'title' => 'Call Source'],
            ['data' => 'reason_text', 'name' => 'reason_text', 'title' => 'Result'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created'],
            ['data' => 'duration', 'name' => 'duration', 'title' => 'Call Duration'],
            // ['data' => 'email', 'name' => 'email', 'title' => 'Email'],
            // ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created At'],
            // ['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Updated At'],
        ]);

        return view('admin.call-records.index', $this->data);
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
