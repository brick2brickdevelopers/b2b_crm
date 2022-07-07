<?php

namespace Modules\RestAPI\Http\Requests\Employee;

use Modules\RestAPI\Http\Requests\BaseRequest;

class CreateRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        // Plus he needs to have notices module enabled from settings
        return in_array('employees', $user->modules);
    }

    public function rules()
    {
        return [
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required|min:6",
            'slack_username' => 'nullable|unique:employee_details,slack_username',
            'hourly_rate' => 'nullable|numeric',
            'last_date' => 'nullable|after_or_equal:joining_date',
            "employee_detail.employee_id" => "required|unique:employee_details,employee_id",
            "employee_detail.joining_date" => "required",
            'employee_detail.department.id' => 'required|exists:teams,id',
            'employee_detail.designation.id' => 'required|exists:designations,id',
        ];
    }
}
