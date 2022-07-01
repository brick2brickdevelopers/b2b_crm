<?php

namespace Modules\RestAPI\Http\Requests\LeaveType;

use Modules\RestAPI\Http\Requests\BaseRequest;

class CreateRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        return in_array('leaves', $user->modules) && $user->hasRole('admin');
    }

    public function rules()
    {
        return [
            'type_name' => 'required',
            'color' => 'required',
            'no_of_leaves' => 'required'
        ];
    }
}
