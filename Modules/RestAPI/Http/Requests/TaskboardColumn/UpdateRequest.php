<?php

namespace Modules\RestAPI\Http\Requests\TaskboardColumn;

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
            'column_name' =>'sometimes|required',
            'label_color' =>'sometimes|required',
        ];
    }
}
