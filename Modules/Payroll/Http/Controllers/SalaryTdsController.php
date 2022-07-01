<?php

namespace Modules\Payroll\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\Admin\AdminBaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Payroll\Entities\PayrollSetting;
use Modules\Payroll\Entities\SalaryTds;
use Modules\Payroll\Http\Requests\StoreSalaryTds;

class SalaryTdsController extends AdminBaseController
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
        $this->salaryTds = SalaryTds::orderBy('id', 'asc')->get();
        $this->payrollSetting = PayrollSetting::firstOrCreate(['company_id' => company()->id]);
        return view('payroll::salary-tds.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('payroll::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(StoreSalaryTds $request)
    {
        SalaryTds::create($request->all());
        return Reply::success(__('messages.recordSaved'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('payroll::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $this->salaryTds = SalaryTds::find($id);
        return view('payroll::salary-tds.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(StoreSalaryTds $request, $id)
    {
        SalaryTds::where('company_id', $id)->update([
            'salary_from' => $request->salary_from,
            'salary_to' => $request->salary_to,
            'salary_percent' => $request->salary_percent
        ]);

        return Reply::success(__('messages.updatedSuccessfully'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        SalaryTds::destroy($id);
        return Reply::success(__('messages.deleteSuccess'));
    }

    public function status(Request $request)
    {
        PayrollSetting::where('company_id', company()->id)->update([
            'tds_status' => $request->status,
            'finance_month' => $request->finance_month,
            'tds_salary' => $request->tdsSalary
        ]);

        return Reply::success(__('messages.updatedSuccessfully'));
    }

}
