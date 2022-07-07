<?php

namespace Modules\RestAPI\Http\Controllers;

use App\EmployeeDetails;
use App\Role;
use Froiden\RestAPI\ApiController;
use Froiden\RestAPI\ApiResponse;
use Illuminate\Support\Facades\Log;
use Modules\RestAPI\Entities\Employee;
use Modules\RestAPI\Http\Requests\Employee\IndexRequest;
use Modules\RestAPI\Http\Requests\Employee\CreateRequest;
use Modules\RestAPI\Http\Requests\Employee\ShowRequest;
use Modules\RestAPI\Http\Requests\Employee\UpdateRequest;
use Modules\RestAPI\Http\Requests\Employee\DeleteRequest;

class EmployeeController extends ApiController
{

    protected $model = Employee::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = CreateRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $showRequest = ShowRequest::class;
    protected $deleteRequest = DeleteRequest::class;

    public function modifyIndex($query)
    {
        return $query->visibility();
    }
    public function modifyShow($query)
    {
        return $query->withoutGlobalScope('active');
    }

    public function modifyDelete($query)
    {
        return $query->withoutGlobalScope('active');
    }
    public function modifyUpdate($query)
    {
        return $query->withoutGlobalScope('active');
    }

    public function stored(Employee $employee)
    {
        $employeeDetail = request()->all('employee_detail')['employee_detail'];
        $employee->employeeDetail()->create($employeeDetail);

        // To add custom fields data
        if (request()->get('custom_fields_data')) {
            $employee->employeeDetail()->updateCustomFieldData(request()->get('custom_fields_data'));
        }

        $employeeRole = Role::where('name', 'employee')->first();
        $employee->attachRole($employeeRole);

        return $employee;
    }

    public function updating(Employee $employee)
    {
        $data = request()->all('employee_detail')['employee_detail'];
        $data['department_id'] = $data['department']['id'];
        $data['designation_id'] = $data['designation']['id'];
        unset($data['designation']);
        unset($data['department']);
        $employee->employeeDetail()->update($data);
        return $employee;
    }

    public function lastEmployeeID()
    {
        $lastEmployeeID = EmployeeDetails::max('id');
        return ApiResponse::make(null, ['id' => $lastEmployeeID]);
    }
}
