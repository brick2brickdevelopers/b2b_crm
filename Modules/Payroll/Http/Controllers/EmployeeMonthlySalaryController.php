<?php

namespace Modules\Payroll\Http\Controllers;

use App\Designation;
use App\Helper\Reply;
use App\Http\Controllers\Admin\AdminBaseController;
use App\Team;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Payroll\Entities\EmployeeMonthlySalary;
use Modules\Payroll\Entities\EmployeePayrollCycle;
use Modules\Payroll\Entities\PayrollCycle;
use Modules\Payroll\Http\Requests\StoreEmployyeMonthlySalary;
use Yajra\DataTables\Facades\DataTables;

class EmployeeMonthlySalaryController extends AdminBaseController
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
        
        return view('payroll::employee-salary.index', $this->data);
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
        if ($request->amount > 0) {
            
            EmployeeMonthlySalary::create(
                [
                    'user_id' => $request->user_id,
                    'amount' => $request->amount,
                    'type' => $request->type,
                    'date' => Carbon::now()->timezone($this->global->timezone)->toDateString(),
                ]
            );
        }
        return Reply::success(__('messages.recordSaved'));

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $this->employeeSalary = EmployeeMonthlySalary::employeeNetSalary($id);
        $this->employee = User::find($id);
        $this->salaryHistory = EmployeeMonthlySalary::where('user_id', $id)->orderBy('date', 'asc')->get();
        return view('payroll::employee-salary.show', $this->data);        
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $this->employeeSalary = EmployeeMonthlySalary::employeeNetSalary($id);
        $this->employee = User::find($id);
        return view('payroll::employee-salary.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(StoreEmployyeMonthlySalary $request, $id)
    {
        EmployeeMonthlySalary::create(
            [
                'user_id' => $id,
                'amount' => $request->amount,
                'type' => $request->type,
                'date' =>  Carbon::createFromFormat($this->global->date_format, $request->date)->format('Y-m-d'),
            ]
        );

        $employeeCycle = EmployeePayrollCycle::where('user_id', $id)->first();
        if(!$employeeCycle){
            $payrollCycle = PayrollCycle::where('cycle', 'monthly')->first();
            $employeeCycle = new EmployeePayrollCycle();
            $employeeCycle->user_id = $id;
            $employeeCycle->payroll_cycle_id = $payrollCycle->id;
            $employeeCycle->save();
        }


        return Reply::success(__('messages.recordSaved'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        EmployeeMonthlySalary::destroy($id);
        return Reply::success(__('messages.deleteSuccess'));
    }

    public function data(Request $request)
    {
        $this->cycles = PayrollCycle::all();

        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->leftJoin('employee_details', 'employee_details.user_id', '=', 'users.id')
            ->leftJoin('designations', 'employee_details.designation_id', '=', 'designations.id')
            ->leftJoin('employee_salary_groups', 'employee_salary_groups.user_id', '=', 'users.id')
            ->leftJoin('salary_groups', 'salary_groups.id', '=', 'employee_salary_groups.salary_group_id')
            ->leftJoin('employee_monthly_salaries', 'employee_monthly_salaries.user_id', '=', 'users.id')
            ->leftJoin('employee_payroll_cycle', 'employee_payroll_cycle.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.image','employee_payroll_cycle.payroll_cycle_id', 'designations.name as designation_name',
                'salary_groups.group_name', 'employee_monthly_salaries.allow_generate_payroll')
            ->where('roles.name', '<>', 'client');

        
        if ($request->designation != 'all' && $request->designation != '') {
            $users = $users->where('employee_details.designation_id', $request->designation);
        }

        if ($request->department != 'all' && $request->department != '') {
            $users = $users->where('employee_details.department_id', $request->department);
        }

        $users = $users->groupBy('users.id')->get()->makeHidden('unreadNotifications');

        return DataTables::of($users)

            ->addColumn('salary_cycle', function ($row) {

               $details = '';
               $details .= '<select name="cycle"  data-user-id="' . $row->id . '"  class="form-control payroll-cycle">';
               $selected = '';
               foreach($this->cycles as $cycle){

                   if($row->payroll_cycle_id == $cycle->id){
                       $selected = 'selected';
                   }
                   $details .= '<option '.$selected.' value="'.$cycle->id.'">'.__('payroll::app.menu.'.$cycle->cycle).'</option>';
                   $selected = '';
               }

                $details .= '</select>';
               return $details;

            })
            ->editColumn('allow_generate_payroll', function ($row) {

               $details = '';
               $details .= '<select name="status"  data-user-id="' . $row->id . '"  class="form-control payroll-status">';
               $selected = ($row->allow_generate_payroll == 'yes' && $row->allow_generate_payroll != "")?'selected':"";
               $details .= '<option '.$selected.' value="yes">'.__('app.yes').'</option>';
               $selected = ($row->allow_generate_payroll == 'no' || $row->allow_generate_payroll == "")?'selected':"";
               $details .= '<option '.$selected.' value="no">'.__('app.no').'</option>';
               $details .= '</select>';
               return $details;
            })
            ->addColumn('action', function ($row) {

                $salary = EmployeeMonthlySalary::employeeNetSalary($row->id);

                if ($salary['netSalary'] > 0) {
                    $details = '<label>'.__('payroll::modules.payroll.grossEarning').': </label> ' . $this->global->currency->currency_symbol.$salary['netSalary'].'<br>';

                    $details.= '<a href="javascript:;" class="btn btn-success btn-outline btn-xs update-salary"
                    data-user-id="' . $row->id . '" ><i class="fa fa-plus" aria-hidden="true"></i> '.__('payroll::modules.payroll.updateSalary').'</a>';

                    $details.= ' <a href="javascript:;" class="btn btn-info btn-outline btn-xs salary-history"
                    data-user-id="' . $row->id . '" ><i class="fa fa-eye" aria-hidden="true"></i> '.__('payroll::modules.payroll.salaryHistory').'</a>';
                    return $details;
                } else {
                    return '
                    <label>'.__('payroll::modules.payroll.initialSalary').'</label><br>
                    <input type="text" class="form-control" id="initial-salary-'.$row->id.'" value="0">
                    <a href="javascript:;" class="btn btn-success btn-outline save-initial-salary"
                       data-user-id="' . $row->id . '" ><i class="fa fa-check" aria-hidden="true"></i></a>';
                }
            })
            ->editColumn('name', function ($row) {

                $image = '<img src="' . $row->image_url . '"alt="user" class="img-circle" width="30"> ';

                $designation = ($row->designation_name) ? ucwords($row->designation_name) : ' ';

                return  '<div class="row"><div class="col-sm-3 col-xs-4">' . $image . '</div><div class="col-sm-9 col-xs-8"><a href="' . route('admin.employees.show', $row->id) . '">' . ucwords($row->name) . '</a><br><span class="text-muted font-12">' . $designation . '</span></div></div>';
            })
            ->addIndexColumn()
            ->rawColumns(['name', 'action', 'id', 'salary_cycle', 'allow_generate_payroll'])
            ->make(true);
    }

    public function employeePayrollCycle(Request $request)
    {
        $employeeCycle = EmployeePayrollCycle::where('user_id', $request->user_id)->first();
        if(!$employeeCycle){
            $employeeCycle = new EmployeePayrollCycle();
            $employeeCycle->user_id = $request->user_id;
        }
        $employeeCycle->payroll_cycle_id = $request->cycle;
        $employeeCycle->save();

        return Reply::success(__('messages.updateSuccess'));
    }
    public function employeePayrollStatus(Request $request)
    {
        $employeeMonthly = EmployeeMonthlySalary::where('user_id', $request->user_id)->first();

        if(!is_null($employeeMonthly)){
            $employeeMonthly->allow_generate_payroll = $request->status;
            $employeeMonthly->save();

        }
        else{
            $employeeMonthly =  new EmployeeMonthlySalary();
            $employeeMonthly->user_id = $request->user_id;
            $employeeMonthly->amount = 0;
            $employeeMonthly->type = 'initial';
            $employeeMonthly->allow_generate_payroll = $request->status;
            $employeeMonthly->date = Carbon::now()->timezone($this->global->timezone)->toDateString();
            $employeeMonthly->save();

            $employeeCycle = EmployeePayrollCycle::where('user_id', $request->user_id)->first();
            if(is_null($employeeCycle)){
                $payrollCycle = PayrollCycle::where('cycle', 'monthly')->first();
                $employeeCycle = new EmployeePayrollCycle();
                $employeeCycle->user_id = $request->user_id;
                $employeeCycle->payroll_cycle_id = $payrollCycle->id;
                $employeeCycle->save();
            }
        }
        return Reply::success(__('messages.updateSuccess'));

    }

}
