<?php

namespace Modules\RestAPI\Http\Requests\RestAPISetting;

use Modules\RestAPI\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'fcm_key' => 'required'
        ];
    }
}
