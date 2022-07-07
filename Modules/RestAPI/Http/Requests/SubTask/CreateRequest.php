<?php

namespace Modules\RestAPI\Http\Requests\SubTask;

use Modules\RestAPI\Http\Requests\BaseRequest;

class CreateRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' =>'required'
        ];
    }
}
