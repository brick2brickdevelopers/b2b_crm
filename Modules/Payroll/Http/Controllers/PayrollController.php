<?php

namespace Modules\Payroll\Http\Controllers;

use App\Attendance;
use App\AttendanceSetting;
use App\Designation;
use App\EmployeeDetails;
use App\Expense;
use App\Helper\Reply;
use App\Holiday;
use App\Http\Controllers\Admin\AdminBaseController;
use App\Leave;
use App\ProjectTimeLog;
use App\Team;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Payroll\Entities\EmployeeMonthlySalary;
use Modules\Payroll\Entities\EmployeeSalaryGroup;
use Modules\Payroll\Entities\PayrollCycle;
use Modules\Payroll\Entities\PayrollSetting;
use Modules\Payroll\Entities\SalaryPaymentMethod;
use Modules\Payroll\Entities\SalarySlip;
use Modules\Payroll\Entities\SalaryTds;
use Modules\Payroll\Notifications\SalaryStatusEmail;
use Yajra\DataTables\Facades\DataTables;

class PayrollController extends AdminBaseController
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
        $this->payrollCycles = PayrollCycle::all();

        $now = Carbon::now();
        $this->year = $now->format('Y');
        $this->month = $now->format('m');
        $this->salaryPaymentMethods = SalaryPaymentMethod::all();

        return view('payroll::payroll.index', $this->data);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $this->salarySlip = SalarySlip::with('user', 'user.employeeDetail', 'salary_group', 'salary_payment_method')->findOrFail($id);
        if ($this->user->hasRole('admin') || $this->salarySlip->user_id == $this->user->id) {
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
        } else {
            abort(403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
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
        $this->salaryPaymentMethods = SalaryPaymentMethod::all();
        return view('payroll::payroll.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $grossEarning = $request->basic_salary;
        $totalDeductions = 0;
        $reimbursement = $request->expense_claims;
        $earningsName = $request->earnings_name;
        $earnings = $request->earnings;
        $deductionsName = $request->deductions_name;
        $deductions = $request->deductions ? $request->deductions : array();
        $extraEarningsName = $request->extra_earnings_name;
        $extraEarnings = $request->extra_earnings;
        $extraDeductionsName = $request->extra_deductions_name;
        $extraDeductions = $request->extra_deductions;

        $earningsArray = array();
        $deductionsArray = array();
        $extraEarningsArray = array();
        $extraDeductionsArray = array();

        if ($earnings != "") {
            foreach ($earnings as $key => $value) {
                $earningsArray[$earningsName[$key]] = floatval($value);
                $grossEarning = $grossEarning + $earningsArray[$earningsName[$key]];
            }
        }

        foreach ($deductions as $key => $value) {
            $deductionsArray[$deductionsName[$key]] = floatval($value);
            $totalDeductions = $totalDeductions + $deductionsArray[$deductionsName[$key]];
        }

        $salaryComponents = [
            'earnings' => $earningsArray,
            'deductions' => $deductionsArray
        ];
        $salaryComponentsJson = json_encode($salaryComponents);

        if ($extraEarnings != "") {
            foreach ($extraEarnings as $key => $value) {
                $extraEarningsArray[$extraEarningsName[$key]] = floatval($value);
                $grossEarning = $grossEarning + $extraEarningsArray[$extraEarningsName[$key]];
            }
        }

        if ($extraDeductions != "") {
            foreach ($extraDeductions as $key => $value) {
                $extraDeductionsArray[$extraDeductionsName[$key]] = floatval($value);
                $totalDeductions = $totalDeductions + $extraDeductionsArray[$extraDeductionsName[$key]];
            }
        }

        $extraSalaryComponents = [
            'earnings' => $extraEarningsArray,
            'deductions' => $extraDeductionsArray
        ];
        $extraSalaryComponentsJson = json_encode($extraSalaryComponents);

        $netSalary = $grossEarning - $totalDeductions + $reimbursement;

        $salarySlip = SalarySlip::findOrFail($id);

        if ($request->paid_on != "") {
            $salarySlip->paid_on = Carbon::createFromFormat($this->global->date_format, $request->paid_on)->format('Y-m-d');
        }

        if ($request->salary_payment_method_id != "") {
            $salarySlip->salary_payment_method_id = $request->salary_payment_method_id;
        }

        $salarySlip->status = $request->status;
        $salarySlip->expense_claims = $request->expense_claims;
        $salarySlip->basic_salary = $request->basic_salary;
        $salarySlip->salary_json = $salaryComponentsJson;
        $salarySlip->extra_json = $extraSalaryComponentsJson;
        $salarySlip->tds = isset($deductionsArray['TDS']) ? $deductionsArray['TDS'] : 0;
        $salarySlip->total_deductions = round(($totalDeductions), 2);
        $salarySlip->net_salary = round(($netSalary), 2);
        $salarySlip->gross_salary = round(($grossEarning), 2);
        $salarySlip->save();

        return Reply::redirect(route('admin.payroll.index'), __('messages.updateSuccess'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        SalarySlip::destroy($id);

        return Reply::success(__('messages.deleteSuccess'));
    }

    public function data(Request $request)
    {
        $startDate = null;
        $endDate   = null;
        if(!is_null($request->month) && $request->month != "null" && $request->month != ""){
            $explode = explode(' ', $request->month);
            $startDate = trim($explode[0]);
            $endDate   = trim($explode[1]);
        }

        $users = User::withoutGlobalScope('active')->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->leftJoin('employee_details', 'employee_details.user_id', '=', 'users.id')
            ->leftJoin('designations', 'employee_details.designation_id', '=', 'designations.id')
            ->join('salary_slips', 'salary_slips.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->join('employee_payroll_cycle', 'employee_payroll_cycle.user_id', '=', 'users.id')
            ->join('payroll_cycles', 'payroll_cycles.id', '=', 'employee_payroll_cycle.payroll_cycle_id')
            ->select('users.id', 'users.name', 'users.email', 'users.image', 'designations.name as designation_name', 'salary_slips.net_salary', 'payroll_cycles.cycle',
                'salary_slips.paid_on', 'salary_slips.status as salary_status', 'salary_slips.id as salary_slip_id', 'salary_slips.salary_from','salary_slips.month',
                'salary_slips.year', 'salary_slips.salary_to')
            ->where('roles.name', '<>', 'client')
            ->where('salary_slips.payroll_cycle_id', $request->payrollCycle);

       if(!is_null($startDate) && !is_null($endDate)){
            $users = $users->whereRaw('Date(salary_slips.salary_from) = ?', [$startDate]);
            $users = $users->whereRaw('Date(salary_slips.salary_to) = ?', [$endDate]);
        }

        $users = $users->where('salary_slips.year', $request->year)
            ->groupBy('users.id')
            ->orderBy('users.id', 'asc')
            ->get()
            ->makeHidden('unreadNotifications');

        return DataTables::of($users)

            ->addColumn('action', function ($row) {
                return '
                    <a href="javascript:;" data-salary-slip-id="' . $row->salary_slip_id . '" class="btn btn-success btn-circle show-salary-slip"
                    ><i class="fa fa-search" aria-hidden="true"></i></a> 

                    <a href="' . route('admin.payroll.edit', $row->salary_slip_id) . '" class="btn btn-info btn-circle"
                      data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>

                      <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-salary-id="' . $row->salary_slip_id . '" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
            })
            ->editColumn('name', function ($row) {

                $image = '<img src="' . $row->image_url . '"alt="user" class="img-circle" width="30"> ';

                $designation = ($row->designation_name) ? ucwords($row->designation_name) : ' ';

                return  '<div class="row"><div class="col-sm-3 col-xs-4">' . $image . '</div><div class="col-sm-9 col-xs-8"><a href="' . route('admin.employees.show', $row->id) . '" >' . ucwords($row->name) . '</a><br><span class="text-muted font-12">' . $designation . '</span></div></div>';
            })
            ->editColumn('salary_from', function ($row) {

                if (!is_null($row->salary_from) && !is_null($row->salary_to)) {
                    $start = Carbon::parse($row->salary_from)->format($this->global->date_format);
                    $end = Carbon::parse($row->salary_to)->format($this->global->date_format);
                    return $start.' '.__('app.to').' '.$end;
                }

                $start = Carbon::parse(Carbon::parse('01-' . $row->month . '-' . $row->year))->startOfMonth()->toDateString();
                $end = Carbon::parse(Carbon::parse('01-' . $row->month . '-' . $row->year))->endOfMonth()->toDateString();

                return $start.' '.__('app.to').' '.$end;
            })
            ->editColumn('id', function ($row) {
                return '<input type="checkbox" data-user-id="' . $row->id . '" name="salary_ids[]" value="' . $row->salary_slip_id . '" />';
            })
            ->editColumn('net_salary', function ($row) {
                return $this->global->currency->currency_symbol . sprintf('%0.2f', $row->net_salary) .' ('.__('app.'.$row->cycle).')';
            })
            ->editColumn('salary_status', function ($row) {
                if ($row->salary_status == 'generated') {
                    return '<label class="label label-inverse">' . __('payroll::modules.payroll.generated') . '</label>';
                } elseif ($row->salary_status == 'review') {
                    return '<label class="label label-info">' . __('payroll::modules.payroll.review') . '</label>';
                } elseif ($row->salary_status == 'locked') {
                    return '<label class="label label-danger">' . __('payroll::modules.payroll.locked') . '</label>';
                } elseif ($row->salary_status == 'paid') {
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
            ->rawColumns(['name', 'action', 'id', 'salary_status'])
            ->make(true);
    }

    public function generatePaySlip(Request $request)
    {
        $month = explode(' ',$request->month);
        $year = $request->year;
        $payrollCycle = $request->payroll_cycle;
        $useAttendance = $request->useAttendance;
        $markApprovedLeavesPaid = $request->markLeavesPaid;
        $markAbsentUnpaid = $request->markAbsentUnpaid;
        $includeExpenseClaims = $request->includeExpenseClaims;
        $addTimelogs = $request->addTimelogs;

        $startDate = Carbon::parse($month[0]);
        $endDate = Carbon::parse($month[1]);
        $lastDayCheck = Carbon::parse($month[1]);
        $payrollCycleData = PayrollCycle::find($payrollCycle);

        $daysInMonth = $startDate->diffInDays($lastDayCheck->addDay()); // days by start and end date

        if ($request->userIds) {
            $users = User::with('employeeDetail')
                ->join('employee_payroll_cycle', 'employee_payroll_cycle.user_id', '=', 'users.id')
                ->join('employee_monthly_salaries', 'employee_monthly_salaries.user_id', '=', 'users.id')
                ->where('employee_payroll_cycle.payroll_cycle_id', $payrollCycle)
                ->where('employee_monthly_salaries.allow_generate_payroll', 'yes')
                ->whereIn('users.id', $request->userIds)->get();
        } else {
            $users = User::withoutGlobalScope('active')->join('role_user', 'role_user.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->select('users.id', 'users.name', 'users.email','users.status', 'users.email_notifications', 'users.created_at', 'users.image', 'users.mobile', 'users.country_id')
                ->join('employee_payroll_cycle', 'employee_payroll_cycle.user_id', '=', 'users.id')
                ->join('employee_monthly_salaries', 'employee_monthly_salaries.user_id', '=', 'users.id')
                ->where('employee_payroll_cycle.payroll_cycle_id', $payrollCycle)
                ->where('roles.name', '<>', 'client')
                ->where('employee_monthly_salaries.allow_generate_payroll', 'yes')
                ->orderBy('users.name', 'asc')
                ->groupBy('users.id')->get();
        }

        foreach ($users as $user) {

            $userId = $user->id;
            $employeeDetails = EmployeeDetails::where('user_id', $userId)->first();
            $joiningDate = Carbon::parse($employeeDetails->joining_date)->setTimezone($this->global->timezone);
            if ($endDate->greaterThan($joiningDate)) {
                if ($useAttendance == 1) {
                    $holidays = Holiday::getHolidayByDates($startDate->toDateString(), $endDate->toDateString())->count(); // Getting Holiday Data

                    $totalWorkingDays = $daysInMonth - $holidays;

                    $presentCount = Attendance::countDaysPresentByUser($startDate, $endDate, $userId); // Getting Attendance Data
                    $absentCount = $totalWorkingDays - $presentCount;

                    $leaveCount = Leave::where('user_id', $userId)
                        ->where('leave_date', '>=', $startDate)
                        ->where('leave_date', '<=', $endDate)
                        ->where('status', 'approved')
                        ->count();

                    if ($markAbsentUnpaid) {
                        if ($markApprovedLeavesPaid) {
                            $presentCount = $presentCount + $leaveCount;
                        }
                    } else {
                        if ($markApprovedLeavesPaid) {
                            $presentCount = $presentCount + $absentCount;
                        } else {
                            $presentCount = $presentCount + $absentCount - $leaveCount;
                        }
                    }

                    $payDays = $presentCount + $holidays;
                } else {
                    $payDays = $daysInMonth;
                }
                $monthCur = $endDate->month;
                $curMonthDays = Carbon::parse('01-' . $monthCur . '-' . $year)->daysInMonth;

                $monthlySalary = EmployeeMonthlySalary::employeeNetSalary($userId, $endDate);
                $perDaySalary = $monthlySalary['netSalary'] / $curMonthDays;
                $payableSalary = $perDaySalary * $payDays;
                $basicSalary = $payableSalary;


                $salaryGroup = EmployeeSalaryGroup::with(['salary_group.components', 'salary_group.components.component'])->where('user_id', $userId)->first();

                $earnings = array();
                $earningsTotal = 0;
                $deductions = array();
                $deductionsTotal = 0;
                $paybableNew = 0;
                $paybalCont = 0;

                if (!is_null($salaryGroup)) {

                    foreach ($salaryGroup->salary_group->components as $components) {
                        $componentValueAmount = ($payrollCycleData->cycle != 'monthly') ? $components->component->{$payrollCycleData->cycle.'_value'} : $components->component->component_value;
                        // calculate earnings
                        if ($components->component->component_type == 'earning') {

                            if ($components->component->value_type == 'fixed') {

                                $basicSalary = $basicSalary - $componentValueAmount;
                                 if($basicSalary < 0) {
                                    $basicSalary = 0;
                                }
                                $earnings[$components->component->component_name] = floatval($componentValueAmount);
                            } else {
                                $componentValue = ($componentValueAmount / 100) * $payableSalary;
                                $basicSalary = $basicSalary - $componentValue;
                                if($basicSalary < 0) {
                                    $basicSalary = 0;
                                }
                                $earnings[$components->component->component_name] = round(floatval($componentValue), 2);
                            }
                            $earningsTotal = $earningsTotal + $earnings[$components->component->component_name];
                            if($earningsTotal > $payableSalary){
                                $paybableNew = $earningsTotal;
                            }

                        } else { // calculate deductions
                            if ($components->component->value_type == 'fixed') {
                                if($componentValueAmount < 0) {
                                    $basicSalary = 0;
                                }
                                // $basicSalary = $basicSalary + $components->component->component_value;
                                $deductions[$components->component->component_name] = floatval($componentValueAmount);
                            } else {
                                $componentValue = ($componentValueAmount / 100) * $payableSalary;
                                if($componentValue < 0) {
                                    $basicSalary = 0;
                                }
                                // $basicSalary = $basicSalary + $componentValue;
                                $deductions[$components->component->component_name] = round(floatval($componentValue), 2);
                            }
                            $deductionsTotal = $deductionsTotal + $deductions[$components->component->component_name];

                            if($earningsTotal < $deductionsTotal){
                                $paybableNew = 0;
                            }
                        }
                    }
                }
                if($paybableNew > $payableSalary){
                    $payableSalary = $paybableNew;
                }

                $salaryTdsTotal = 0;
                $payrollSetting = PayrollSetting::firstOrCreate(['company_id' => company()->id]);

                $today = Carbon::now()->timezone($this->global->timezone);
                $financialyearStart = Carbon::parse($today->year . '-' . $payrollSetting->finance_month . '-01')->setTimezone($this->global->timezone);
                $financialyearEnd = Carbon::parse($today->year . '-' . $payrollSetting->finance_month . '-01')->addYear()->subDays(1)->setTimezone($this->global->timezone);

                if ($payrollSetting->tds_status) {
                    $deductions['TDS'] = 0;

                    $annualSalary = $this->calculateTdsSalary($userId, $joiningDate, $financialyearStart, $financialyearEnd, $endDate);

                    if ($payrollSetting->tds_salary < $annualSalary) {
                        $salaryTds = SalaryTds::orderBy('salary_from', 'asc')->get();
                        $taxableSalary = $annualSalary;
                        $previousLimit = 0;

                        foreach ($salaryTds as $tds) {
                            if ($annualSalary >= $tds->salary_from && $annualSalary <= $tds->salary_to) {
                                $tdsValue = ($tds->salary_percent / 100) * $taxableSalary;
                                $salaryTdsTotal = $salaryTdsTotal + $tdsValue;
                            } elseif ($annualSalary >= $tds->salary_from && $annualSalary >= $tds->salary_to) {
                                $previousLimit = $tds->salary_to - $previousLimit;
                                $taxableSalary = $taxableSalary - $previousLimit;
                                // echo $taxableSalary.'<br>';

                                $tdsValue = ($tds->salary_percent / 100) * $previousLimit;
                                $salaryTdsTotal = $salaryTdsTotal + $tdsValue;
                            }
                        }

                        // return $salaryTdsTotal;
                        $tdsAlreadyPaid = SalarySlip::where('user_id', $userId)->sum('tds');
                        $tdsToBePaid = $salaryTdsTotal - $tdsAlreadyPaid;
                        $monthDiffFromFinYrEnd = $financialyearEnd->diffInMonths($startDate, true) + 1;
                        $deductions['TDS'] = floatval($tdsToBePaid) / $monthDiffFromFinYrEnd;
                        $perDayTds = ($deductions['TDS']/30);
                        $deductions['TDS'] = $perDayTds*$daysInMonth;
                        // $basicSalary = $basicSalary + $deductions['TDS'];
                        $deductionsTotal = $deductionsTotal + $deductions['TDS'];
                        $deductions['TDS'] = round($deductions['TDS'], 2);
                    }
                }

                // return $deductions;

                $expenseTotal = 0;
                if ($includeExpenseClaims) {
                    $expenseTotal = Expense::where(DB::raw('DATE(purchase_date)'), '>=', $startDate)
                        ->where(DB::raw('DATE(purchase_date)'), '<=', $endDate)
                        ->where('user_id', $userId)
                        ->where('status', 'approved')
                        ->where('can_claim', 1)
                        ->sum('price');
                    $payableSalary = $payableSalary + $expenseTotal;
                }

                if ($addTimelogs) {
                    $earnings['Time Logs'] = ProjectTimeLog::where(DB::raw('DATE(start_time)'), '>=', $startDate)
                        ->where(DB::raw('DATE(start_time)'), '<=', $endDate)
                        ->where('user_id', $userId)
                        ->where('approved', 1)
                        ->sum('earnings');
                    $payableSalary = $payableSalary + $earnings['Time Logs'];
                    $earnings['Time Logs'] = round($earnings['Time Logs'], 2);
                }

                $salaryComponents = [
                    'earnings' => $earnings,
                    'deductions' => $deductions
                ];
//                if(array_sum($earnings))

                $salaryComponentsJson = json_encode($salaryComponents);
                // return $deductions;
//                 return $earnings;
                // return $earningsTotal;
                // return $deductionsTotal;
                // return $salaryComponents;
                // return $basicSalary;
                // return $payableSalary;
//                dd($basicSalary);
                $data = [
                    'user_id' => $userId,
                    'salary_group_id' => (($salaryGroup) ? $salaryGroup->salary_group_id : null),
                    'basic_salary' => round($basicSalary, 2),
                    'monthly_salary' => round($monthlySalary['netSalary'], 2),
                    'net_salary' => ($deductionsTotal < $payableSalary) ? round(($payableSalary - $deductionsTotal), 2) : 0,
                    'gross_salary' => ($expenseTotal < $payableSalary) ? round(($payableSalary - $expenseTotal), 2) : 0,
                    'total_deductions' => round(($deductionsTotal), 2),
                    'month' => $startDate->month,
                    'payroll_cycle_id' => $payrollCycle,
                    'salary_from' => $startDate->format('Y-m-d'),
                    'salary_to' => $endDate->format('Y-m-d'),
                    'year' => $year,
                    'salary_json' => $salaryComponentsJson,
                    'expense_claims' => $expenseTotal,
                    'pay_days' => $payDays,
                ];

                if ($payrollSetting->tds_status) {
                    $data['tds'] = $deductions['TDS'];
                }

                // return $data;
                SalarySlip::where('user_id', $userId)
                    ->where(function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('salary_from', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                            ->orWhereBetween('salary_to', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
                    })
                    ->where('year', $year)->delete();

                SalarySlip::create($data);
            }
        }

        return Reply::dataOnly(['status' => 'success']);
    }

    protected function calculateTdsSalary($userId, $joiningDate, $financialyearStart, $financialyearEnd, $payrollMonthEndDate)
    {
        $totalEarning = 0;

        if ($joiningDate->greaterThan($financialyearStart)) {
            $monthlySalary = EmployeeMonthlySalary::employeeNetSalary($userId);
            $currentSalary = $initialSalary = $monthlySalary['initialSalary'];
        } else {
            $monthlySalary = EmployeeMonthlySalary::employeeNetSalary($userId, $financialyearStart);
            $currentSalary = $initialSalary = $monthlySalary['netSalary'];
        }

        $increments = EmployeeMonthlySalary::employeeIncrements($userId);
        $lastIncrement = null;

        foreach ($increments as $increment) {
            $incrementDate = Carbon::parse($increment->date);
            if ($payrollMonthEndDate->greaterThan($incrementDate)) {
                if (is_null($lastIncrement)) {
                    $payDays = $incrementDate->diffInDays($joiningDate, true);
                    $perDaySalary = ($initialSalary / 30); //30 is taken as no of days in a month
                    $totalEarning = $payDays * $perDaySalary;
                    $lastIncrement = $incrementDate;
                    $currentSalary = $increment->amount + $initialSalary;
                } else {
                    $payDays = $incrementDate->diffInDays($lastIncrement, true);
                    $perDaySalary = ($currentSalary / 30);
                    $totalEarning = $totalEarning + ($payDays * $perDaySalary);
                    $lastIncrement = $incrementDate;
                    $currentSalary = $increment->amount + $currentSalary;
                }
            }
        }

        if (!is_null($lastIncrement)) {
            $payDays = $financialyearEnd->diffInDays($lastIncrement, true);
            $perDaySalary = ($currentSalary / 30);
            $totalEarning = $totalEarning + ($payDays * $perDaySalary);
        } else {
            $payDays = $financialyearEnd->diffInDays($joiningDate, true);
            $perDaySalary = ($initialSalary / 30); //30 is taken as no of days in a month
            $totalEarning = $payDays * $perDaySalary;
        }

        return $totalEarning;
    }

    public function updateStatus(Request $request)
    {
        $salarySlips = SalarySlip::whereIn('id', $request->salaryIds)->get();
        $salarySlipsTotal = SalarySlip::whereIn('id', $request->salaryIds)->sum('net_salary');

        $data = [
            "status" => $request->status
        ];

        if ($request->status == "paid") {
            $data['salary_payment_method_id'] = $request->paymentMethod;
            $data['paid_on'] = Carbon::createFromFormat($this->global->date_format, $request->paidOn)->toDateString();
        } else {
            $data['salary_payment_method_id'] = null;
            $data['paid_on'] = null;
        }

        foreach ($salarySlips as $key => $value) {
            $salary = SalarySlip::find($value->id);
            $salary->update($data);

            if ($request->status != 'generated') {
                $notifyUser = User::find($salary->user_id);
                $notifyUser->notify(new SalaryStatusEmail($salary));
            }
        }

        if ($request->add_expenses == "yes") {
            $expense = new Expense();
            $expenseTitle = null;

            if(isset($salarySlips[0])){
                $firstSalary = $salarySlips[0];
                $payrollCycle = PayrollCycle::find($firstSalary->payroll_cycle_id);

                if(!is_null($payrollCycle) && $payrollCycle->cycle != 'monthly') {
                    $expenseTitle = __('payroll::modules.payroll.salaryExpenseHeadingWithoutMonth') . ' ' . $firstSalary->salary_from->format($this->global->date_format).' - '.$firstSalary->salary_to->format($this->global->date_format);
                }
            }

            if(is_null($expenseTitle)){
                $expenseTitle = __('payroll::modules.payroll.salaryExpenseHeading') . ' ' . Carbon::createFromFormat($this->global->date_format, $request->paidOn)->format('F Y');
            }

            $expense->item_name = $expenseTitle;
            $expense->purchase_date = Carbon::createFromFormat($this->global->date_format, $request->paidOn)->toDateString();
            $expense->purchase_from = Carbon::createFromFormat($this->global->date_format, $request->paidOn)->format('F Y');
            $expense->price = $salarySlipsTotal;
            $expense->currency_id = $this->global->currency_id;
            $expense->user_id = user()->id;
            $expense->status = 'approved';
            $expense->can_claim = 0;
            $expense->save();
        }

        return Reply::dataOnly(['status' => 'success']);
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
        //   return $pdf->stream();
        return $pdf->download($this->salarySlip->user->employeeDetail->employee_id . '-' . date('F', mktime(0, 0, 0, $this->salarySlip->month, 10)) . "-" . $this->salarySlip->year . '.pdf');
    }

    public function getCycleData(Request $request){
        $payrollCycle = PayrollCycle::find($request->payrollCycle);
        $currentDate = Carbon::now();
        $this->current = 0;
        if($payrollCycle->cycle == 'weekly')
        {
            $year = Carbon::now()->year;
            $dateData = [];
            $weeks = 52;
            $carbonFirst = new Carbon('first Monday of January '.$year);
            for ($i = 1; $i <= $weeks; $i++) {
                $dateData['start_date'] []= $carbonFirst->toDateString();
                $endDate = $carbonFirst->addWeek();
                $dateData['end_date'] []= $endDate->subDay()->toDateString();
                $index = ($i > 1) ? ($i-1) : 0;
                $startDateData = Carbon::parse($dateData['start_date'][$index]);
                if($currentDate->between($startDateData, $endDate) ){
                    $this->current = $index;
                }
                $carbonFirst = $endDate->addDay();
            }
            if($request->has('with_view')){
                $this->results = $dateData;
                $this->cycle = 'weekly';
                $this->month = Carbon::now()->month;

                $view =  view('payroll::payroll.cycle', $this->data)->render();
                return Reply::dataOnly(['view'=> $view]);
            }
            return $dateData;
        }
        if($payrollCycle->cycle == 'biweekly')
        {
            $year = Carbon::now()->year;
            $dateData = [];
            $weeks = 26;
            $carbonFirst = new Carbon('first Monday of January '.$year);

            $this->current = 0;
            $index = 0;
            for ($i = 1; $i <= $weeks; $i++) {
                $dateData['start_date'] []= $carbonFirst->format('Y-m-d');
                $endDate = $carbonFirst->addWeeks(2);
                $dateData['end_date'] []= $endDate->subDay()->toDateString();
                $index = ($i > 1) ? ($i-1) : 0;
                $startDateData = Carbon::parse($dateData['start_date'][$index]);
                if($currentDate->between($startDateData, $endDate) ){
                    $this->current = $index;
                }
                $carbonFirst = $endDate->addDay();

            }

            if($request->has('with_view')){
                $this->results = $dateData;
                $this->cycle = 'biweekly';
                $this->month = Carbon::now()->month;

            $view =  view('payroll::payroll.cycle', $this->data)->render();
            return Reply::dataOnly(['view'=> $view]);
            }
            return $dateData;
        }
        if($payrollCycle->cycle == 'semimonthly')
        {
            $startDay = 1;
            $endDay = 15;
            $startSecondDay = 16;
            $endSecondDay = 30;
            $year = Carbon::now()->year;
            $dateData = [];
            $datecheckData = [];
            $months = ['01', '02', '03', '04', '05', '06','07', '08', '09', '10', '11', '12'];
            $i = 0;
            foreach ($months as $index => $month) {
                $date = Carbon::createFromDate($year, $month);
                $daysInMonth = $date->daysInMonth;

                $dateData['start_date'] [] = $startDateData = Carbon::createFromDate($year,$month,$startDay)->toDateString();

                $dateData['end_date'] [] = $endDateData = Carbon::createFromDate($year,$month,$endDay)->toDateString();

                if($currentDate->between($startDateData, $endDateData)){
                    $this->current = $i;
                }
                $i++;
                $dateData['start_date'] []= $startDateDataNew = Carbon::createFromDate($year,$month,$startSecondDay)->toDateString();

                if($endSecondDay > $daysInMonth){
                    $dateData['end_date'] []= $endDateDataNew = Carbon::createFromDate($year,$month,$daysInMonth)->toDateString();
                }
                else{
                    $dateData['end_date'] []= $endDateDataNew = Carbon::createFromDate($year,$month,$endSecondDay)->toDateString();
                }

                if($currentDate->between($startDateDataNew, $endDateDataNew)){
                    $this->current = $i;
                }
                $i++;
            }
            if($request->has('with_view')){
                $this->results = $dateData;
                $this->cycle = 'semimonthly';
                $this->month = Carbon::now()->month;

            $view =  view('payroll::payroll.cycle', $this->data)->render();
            return Reply::dataOnly(['view'=> $view]);
            }
            return $dateData;
        }
        if($payrollCycle->cycle == 'monthly')
        {
            $this->months = ['january','february','march','april','may','june','july','august','september','october','november','december'];
            $year = Carbon::now()->year;
            $dateData = [];
            $months = ['01', '02', '03', '04', '05', '06','07', '08', '09', '10', '11', '12'];
            foreach ($months as $month) {
                $date = Carbon::createFromDate($year, $month);
                $dateData['start_date'] []= Carbon::parse(Carbon::parse('01-' . $month . '-' . $year))->startOfMonth()->toDateString();
                $dateData ['end_date'] []= Carbon::parse(Carbon::parse('01-' . $month . '-' . $year))->endOfMonth()->toDateString();
            }
            if($request->has('with_view')){
                $this->results = $dateData;
                $this->cycle = 'monthly';
                $this->month = Carbon::now()->month;
                $view =  view('payroll::payroll.cycle', $this->data)->render();
                return Reply::dataOnly(['view'=> $view]);

            }
            return $dateData;
        }
    }
}
