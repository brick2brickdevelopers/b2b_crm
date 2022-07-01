<?php

namespace Modules\Payroll\Http\Controllers;

use App\Designation;
use App\EmployeeDetails;
use App\Helper\Reply;
use App\Http\Controllers\Member\MemberBaseController;
use App\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Payroll\Entities\PayrollSetting;
use Modules\Payroll\Entities\SalaryPaymentMethod;
use Modules\Payroll\Entities\SalarySlip;
use Yajra\DataTables\Facades\DataTables;

class MemberPayrollController extends MemberBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('payroll::app.menu.payroll');
        $this->pageIcon = 'icon-wallet';
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
        $this->departments = Team::all();
        $this->designations = Designation::all();
        
        $now = Carbon::now();
        $this->year = $now->format('Y');
        $this->month = $now->format('m');
        $this->salaryPaymentMethods = SalaryPaymentMethod::all();
   
        return view('payroll::payroll.member_index', $this->data);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $this->salarySlip = SalarySlip::with('user', 'user.employeeDetail', 'salary_group', 'salary_payment_method')->findOrFail($id);
        $salaryJson = json_decode($this->salarySlip->salary_json, true);
        $this->earnings = $salaryJson['earnings'];
        $this->deductions = $salaryJson['deductions'];
        $extraJson = json_decode($this->salarySlip->extra_json, true);

        if (!is_null($extraJson)) {
            $this->earningsExtra = $extraJson['earnings'];
            $this->deductionsExtra = $extraJson['deductions'];
        } else {
            $this->earningsExtra = "";
            $this->deductionsExtra = "";
        }

        if ($this->earningsExtra == "") {
            $this->earningsExtra = array();
        }

        if ($this->deductionsExtra == "") {
            $this->deductionsExtra = array();
        }

        $this->extraFields = [];
        $this->payrollSetting = PayrollSetting::first();
        if($this->payrollSetting->extra_fields){
            $this->extraFields = json_decode($this->payrollSetting->extra_fields);
        }
        $this->employeeDetail = EmployeeDetails::where('user_id', '=', $this->salarySlip->user->id)->first()->withCustomFields();

        $this->fieldsData = $this->employeeDetail->getCustomFieldGroupsWithFields()->fields;
        $this->fields = $this->fieldsData->filter(function ($value, $key) {
            return in_array($value->id, $this->extraFields);
        })->all();

        $view = view('payroll::payroll.show', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    public function data(Request $request)
    {
        $users = SalarySlip::with('user', 'user.employeeDetail')
            ->where('status', '<>', 'generated')
            ->where('user_id', $this->user->id);
        if ($request->month != '0') {
            $users = $users->where('month', $request->month);
        }
        if ($request->year != '0') {
            $users = $users->where('year', $request->year);
        }

        $users = $users->orderBy('id', 'desc')->get()
            ->makeHidden('unreadNotifications');

        return DataTables::of($users)

            ->addColumn('action', function ($row) {
                return '<a href="javascript:;" class="btn btn-info btn-circle show-salary-slip"
                      data-toggle="tooltip" data-original-title="View" data-salary-slip-id="'.$row->id.'"><i class="fa fa-search" aria-hidden="true"></i></a>

                      <a href="'.route('member.payroll.downloadPdf', md5($row->id)).'" class="btn btn-success btn-circle"
                     ><i class="fa fa-download" aria-hidden="true"></i></a>';
            })
            ->editColumn('month', function ($row) {
                return Carbon::parse($row->year.'-'.$row->month.'-01')->format('F Y');
            })
            ->editColumn('net_salary', function ($row) {
                return $this->global->currency->currency_symbol . sprintf('%0.2f', $row->net_salary);
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 'review') {
                    return '<label class="label label-info">' . __('payroll::modules.payroll.review') . '</label>';
                } elseif ($row->status == 'locked') {
                    return '<label class="label label-danger">' . __('payroll::modules.payroll.locked') . '</label>';
                } elseif ($row->status == 'paid') {
                    return '<label class="label label-success">' . __('payroll::modules.payroll.paid') . '</label>';
                }
                return ucwords($row->salary_status);
            })
            ->editColumn('paid_on', function ($row) {
                if (!is_null($row->paid_on)) {
                    return Carbon::parse($row->paid_on)->format($this->global->date_format);
                } else {
                    return "--";
                }
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function downloadPdf($id)
    {
        $this->salarySlip = SalarySlip::with('user', 'user.employeeDetail', 'salary_group', 'salary_payment_method')->whereRaw('md5(id) = ?', $id)->firstOrFail();
        $salaryJson = json_decode($this->salarySlip->salary_json, true);
        $this->earnings = $salaryJson['earnings'];
        $this->deductions = $salaryJson['deductions'];
        $extraJson = json_decode($this->salarySlip->extra_json, true);

        if (!is_null($extraJson)) {
            $this->earningsExtra = $extraJson['earnings'];
            $this->deductionsExtra = $extraJson['deductions'];
        } else {
            $this->earningsExtra = "";
            $this->deductionsExtra = "";
        }

        if ($this->earningsExtra == "") {
            $this->earningsExtra = array();
        }

        if ($this->deductionsExtra == "") {
            $this->deductionsExtra = array();
        }

        $this->payrollSetting = PayrollSetting::first();
        $this->extraFields = [];
        $this->company = company();

        if($this->payrollSetting->extra_fields){
            $this->extraFields = json_decode($this->payrollSetting->extra_fields);
        }

        $this->employeeDetail = EmployeeDetails::where('user_id', '=', $this->salarySlip->user->id)->first()->withCustomFields();

        $this->fieldsData = $this->employeeDetail->getCustomFieldGroupsWithFields()->fields;
        $this->fields = $this->fieldsData->filter(function ($value, $key) {
            return in_array($value->id, $this->extraFields);
        })->all();
        
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('payroll::payroll.pdfview', $this->data);
        return $pdf->download($this->salarySlip->user->employeeDetail->employee_id .'-'.date('F', mktime(0, 0, 0, $this->salarySlip->month, 10)) . "-" . $this->salarySlip->year . '.pdf');
       
    }

}
