<?php

namespace App\Http\Controllers\Admin;

use App\CallingGroup;
use App\EmployeeDetails;
use App\Helper\Reply;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ContractType\StoreRequest;
use Illuminate\Http\Request;

class CallingGroupController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageIcon = 'icon-list';
        $this->pageTitle = 'Calling Group';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->groups = CallingGroup::with('members', 'members.user')->get();
        // dd($this->data);
        return view('admin.calling-group.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->employee = EmployeeDetails::all();
        return view('admin.calling-group.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $group = new CallingGroup();
        $group->company_id = company()->id;
        $group->calling_group_name = $request->calling_group_name;
        $group->fallback_number = $request->fallback_number;
        $group->employees = json_encode($request->employees);
        $group->is_default = false;
        $group->save();

        return Reply::redirect(route('admin.calling-group.index'), 'Calling Group created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function isDefault(Request $request)
    {
        $checkDefault = CallingGroup::where('is_default', true)->first();
        if ($checkDefault) {
            if ($checkDefault->id == $request->id) {
                $group = CallingGroup::find($request->id);
                $group->is_default = false;
                $group->save();
                return Reply::redirect(route('admin.calling-group.index'), ('Group Deactivated Successfully.'));
            } else {
                return Reply::redirectWithError(route('admin.calling-group.index'), 'Another Group is Already Activated');
            }
        } else {
            $group = CallingGroup::find($request->id);
            if ($group->is_default == true) {
                $group->is_default = false;
            } else {
                $group->is_default = true;
            }
            $group->save();
            return Reply::redirect(route('admin.calling-group.index'), ('Group Activated Successfully.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->employee = EmployeeDetails::all();
        $this->groups = CallingGroup::findOrFail($id);
        // dd($this->groups);
        return view('admin.calling-group.edit', $this->data);
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
        $group = CallingGroup::findOrFail($id);
        $group->company_id = company()->id;
        $group->calling_group_name = $request->calling_group_name;
        $group->fallback_number = $request->fallback_number;
        $group->employees = json_encode($request->employees);
        // $group->is_default = false;
        $group->save();

        return Reply::redirect(route('admin.calling-group.index'), 'Calling Group created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        CallingGroup::destroy($id);
        // return Reply::redirect(route('admin.calling-group.index'), ('Calling Group deleted successfully.'));
        return back();
    }
}
