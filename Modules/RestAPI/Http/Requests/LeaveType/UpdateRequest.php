<?php

namespace Modules\RestAPI\Http\Requests\LeaveType;

use Modules\RestAPI\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        // Either user has role admin or has permission edit_notice
        // Plus he needs to have notices module enabled from settings
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
