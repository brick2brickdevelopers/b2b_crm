<?php

namespace Modules\Payroll\Http\Controllers;

use App\EmployeeDetails;
use App\Helper\Reply;
use App\Http\Controllers\Admin\AdminBaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Payroll\Entities\PayrollSetting;

class PayrollSettingController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('app.menu.payroll') . ' ' . __('app.menu.settings');
        $this->pageIcon = 'icon-settings';
        $this->middleware(function ($request, $next) {
            if (!in_array('payroll', $this->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $employee = new EmployeeDetails();
        $this->fields = $employee->getCustomFieldGroupsWithFields()->fields;
        $this->payrollSetting = PayrollSetting::first();
        $this->extraFields = [];

        if($this->payrollSetting->extra_fields){
            $this->extraFields = json_decode($this->payrollSetting->extra_fields);
        }
        return view('payroll::payroll-setting.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $valArray = [];
        $employee = new EmployeeDetails();
        $fields = $employee->getCustomFieldGroupsWithFields()->fields;
        if($request->has('select_all_field')){
            $valArray = json_encode($fields->pluck('id')->toArray());
        }
        else{
            foreach($fields as $field){
                if($request->has($field->name)){
                    array_push($valArray,$field->id);
                }
            }
            $valArray = json_encode($valArray);
        }

        $PayrollSetting = PayrollSetting::first();
        $PayrollSetting->extra_fields =  $valArray;
        $PayrollSetting->save();

        return Reply::success(__('messages.recordSaved'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //return view('payroll::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
       //
    }
}
