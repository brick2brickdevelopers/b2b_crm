<?php

namespace Modules\RestAPI\Http\Requests\Employee;

use Modules\RestAPI\Entities\Employee;
use Modules\RestAPI\Entities\Notice;
use Modules\RestAPI\Http\Requests\BaseRequest;

class ShowRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();

        if ($user->hasRole('admin')) {
            return true;
        }
        // Either user has role admin or has permission view_notice
        // Plus he needs to have notices module enabled from settings
        $employee = Employee::withoutGlobalScope('active')->find($this->route('employee'));
        return in_array('employees', $user->modules)
            && $employee
            && $employee->visibleTo($user)
            && Employee::isEmployee($this->route('employee'));
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
