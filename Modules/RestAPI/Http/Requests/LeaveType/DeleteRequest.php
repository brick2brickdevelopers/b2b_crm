<?php

namespace Modules\RestAPI\Http\Requests\LeaveType;

use Modules\RestAPI\Http\Requests\BaseRequest;

class DeleteRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        // Either user has role admin or has permission delete_notice
        // Plus he needs to have notices module enabled from settings
        return in_array('leaves', $user->modules) && $user->hasRole('admin');
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
