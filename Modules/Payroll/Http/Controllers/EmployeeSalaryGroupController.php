<?php

namespace Modules\Payroll\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\Admin\AdminBaseController;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Payroll\Entities\EmployeeSalaryGroup;
use Modules\Payroll\Entities\SalaryGroup;
use Modules\Payroll\Entities\SalaryComponent;
use Modules\Payroll\Http\Requests\StoreEmployeeSalaryGroup;
use Yajra\DataTables\Facades\DataTables;

class EmployeeSalaryGroupController extends AdminBaseController
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
        return view('payroll::employee-salary-groups.index', $this->data);
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
    public function store(StoreEmployeeSalaryGroup $request)
    {
        foreach ($request->user_id as $user) {
            EmployeeSalaryGroup::where('user_id', $user)->delete();
            
            EmployeeSalaryGroup::create(
                [
                    'salary_group_id' => $request->salary_group_id,
                    'user_id' => $user
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
        $this->salaryGroup = SalaryGroup::with('employees', 'employees.user', 'employees.user.employeeDetail', 'employees.user.employeeDetail.designation')->findOrFail($id);
        $this->employees =  User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->leftJoin('employee_salary_groups', 'employee_salary_groups.user_id', '=', 'users.id')
            ->leftJoin('salary_groups', 'salary_groups.id', '=', 'employee_salary_groups.salary_group_id')
            ->select('users.id', 'users.name', 'users.email', 'salary_groups.group_name')
            ->where('roles.name', '<>', 'client')
            ->groupBy('users.id')
            ->orderBy('users.name')
            ->get();
  
        return view('payroll::employee-salary-groups.index', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $this->salaryComponent = SalaryComponent::find($id);
        return view('payroll::salary-components.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(StoreEmployeeSalaryGroup $request, $id)
    {
        SalaryComponent::where('id', $id)->update([
            'component_name' => $request->component_name,
            'component_type' => $request->component_type,
            'component_value' => $request->component_value,
            'value_type' => $request->value_type,
        ]);

        return Reply::success(__('messages.updatedSuccessfully'));
    }

    public function data(Request $request)
    {
        $searchString = $request->searchString;
 
        $this->searchResults = User::join('employee_salary_groups', 'users.id', '=', 'employee_salary_groups.user_id')
            ->where('employee_salary_groups.salary_group_id', $request->groupId)
            ->where('users.name', 'like', $searchString.'%')
            ->select('users.id', 'users.name', 'employee_salary_groups.id as empgp_id')
            ->get();
        
        $view = view('payroll::employee-salary-groups.employees-list', $this->data)->render();

        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        EmployeeSalaryGroup::destroy($id);
        return Reply::success(__('messages.deleteSuccess'));
    }
}
