<?php

namespace Modules\RestAPI\Http\Requests\TaskboardColumn;

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
            'column_name' =>'required',
            'label_color' =>'required',
        ];
    }
}
