<?php

namespace Modules\RestAPI\Http\Requests\Holiday;

use Modules\RestAPI\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        // Either user has role admin or has permission edit_holiday
        // Plus he needs to have holidays module enabled from settings
        return in_array('holidays', $user->modules) && ($user->hasRole('admin') || $user->cans('edit_holiday'));
    }

    public function rules()
    {
        return [
            'date' =>'sometimes|required',
            'occassion' =>'sometimes|required'
        ];
    }
}
