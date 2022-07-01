<?php

namespace Modules\RestAPI\Http\Requests\Leave;

use Modules\RestAPI\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        // Either user has role admin or has permission edit_notice
        // Plus he needs to have notices module enabled from settings
        return in_array('leaves', $user->modules) && ($user->hasRole('admin') || $user->cans('edit_leave'));
    }

    public function rules()
    {
        return [
            'type.id' => 'sometimes|required|exists:leave_types,id',
            'status' => 'required'
        ];
    }
}
