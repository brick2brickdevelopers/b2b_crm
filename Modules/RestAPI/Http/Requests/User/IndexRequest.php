<?php

namespace Modules\RestAPI\Http\Requests\User;

use Modules\RestAPI\Http\Requests\BaseRequest;

class IndexRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        // Plus he needs to have notices module enabled from settings
        return $user && in_array('employees', $user->modules) && in_array('employees', $user->modules);
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
