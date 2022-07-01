<?php

namespace Modules\RestAPI\Http\Requests\Employee;

use App\EmployeeDetails;
use Modules\RestAPI\Entities\Employee;
use Modules\RestAPI\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();

        // Either user has role admin or has permission view_notice
        // Plus he needs to have notices module enabled from settings
        $employee = Employee::withoutGlobalScope('active')->find($this->route('employee'));
        return in_array('employees', $user->modules) && $employee && $employee->visibleTo($user);
    }

    public function rules()
    {
        $employee = Employee::withoutGlobalScope('active')->findOrFail($this->route('employee'));
        $detailID = $employee->employeeDetail;

        $rules = [
            "name" => "required",
            'slack_username' => 'nullable|unique:employee_details,slack_username',
            'hourly_rate' => 'nullable|numeric',
            'joining_date' => 'sometimes|required',
            "employee_detail.employee_id" => 'sometimes|required|unique:employee_details,employee_id,'.$detailID->id,
            'employee_detail.department.id' => 'required|exists:teams,id',
            'employee_detail.designation.id' => 'required|exists:designations,id',
            'email' => 'sometimes|required|unique:users,email,'.$this->route('employee'),
            'last_date' => 'sometimes|nullable|after_or_equal:joining_date',
        ];

        if ($detailID) {
            $rules['slack_username'] = 'sometimes|nullable|unique:employee_details,slack_username,'.$detailID->id;
        } else {
            $rules['slack_username'] = 'sometimes|nullable|unique:employee_details,slack_username';
        }
        return $rules;
    }
}
