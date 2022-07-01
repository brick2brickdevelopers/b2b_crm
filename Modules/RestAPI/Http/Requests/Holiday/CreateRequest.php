<?php

namespace Modules\RestAPI\Http\Requests\Holiday;

use Modules\RestAPI\Http\Requests\BaseRequest;

class CreateRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        // Either user has role admin or has permission create_holiday
        // Plus he needs to have holidays module enabled from settings
        return in_array('holidays', $user->modules) && ($user->hasRole('admin') || $user->cans('create_holiday'));
    }

    public function rules()
    {
        return [
            'date' =>'required',
            'occassion' =>'required'
        ];
    }
}
