<?php

namespace Modules\RestAPI\Http\Requests\Attendance;

use Modules\RestAPI\Http\Requests\BaseRequest;

class ClockInRequest extends BaseRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [

        ];
    }
}
