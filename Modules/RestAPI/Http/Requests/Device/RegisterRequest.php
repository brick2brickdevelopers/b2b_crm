<?php

namespace Modules\RestAPI\Http\Requests\Device;

use Modules\RestAPI\Http\Requests\BaseRequest;

class RegisterRequest extends BaseRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'device_id' => 'required|numeric',
            'registration_id' => 'required',
            'status' => 'in:active,inactive'
        ];
    }
}
