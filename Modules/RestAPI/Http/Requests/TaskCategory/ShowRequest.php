<?php

namespace Modules\RestAPI\Http\Requests\TaskCategory;

use Modules\RestAPI\Http\Requests\BaseRequest;

class ShowRequest extends BaseRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
