<?php

namespace Modules\RestAPI\Http\Requests\Auth;

use Modules\RestAPI\Http\Requests\BaseRequest;

class EmailVerifyRequest extends BaseRequest
{

    public function rules()
    {
        return [
            'token' => 'required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
