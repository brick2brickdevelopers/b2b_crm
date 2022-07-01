<?php

namespace Modules\RestAPI\Http\Requests\LeaveType;

use Modules\RestAPI\Http\Requests\BaseRequest;

class IndexRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        return in_array('leaves', $user->modules)
            && ($user->hasRole('admin') || $user->cans('view_leave') || $user->user_other_role ==='employee');
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
