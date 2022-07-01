<?php

namespace Modules\RestAPI\Http\Requests\Leave;

use Modules\RestAPI\Http\Requests\BaseRequest;

class CreateRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        return in_array('leaves', $user->modules)
            && ($user->hasRole('admin') || $user->hasRole('employee') || $user->cans('add_leave'));
    }

    public function rules()
    {
        return [
            'user.id' => 'required|exists:users,id',
            'type.id' => 'required|exists:leave_types,id',
            'duration' => 'required',
            'reason' => 'required',
            'status' => 'required'
        ];
    }
}
