<?php

namespace Modules\RestAPI\Http\Requests\Employee;

use Modules\RestAPI\Http\Requests\BaseRequest;

class IndexRequest extends BaseRequest
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
            //
        ];
    }
}
