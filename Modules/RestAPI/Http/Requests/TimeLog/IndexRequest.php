<?php

namespace Modules\RestAPI\Http\Requests\TimeLog;

use Froiden\RestAPI\Exceptions\ApiException;
use Modules\RestAPI\Entities\ProjectTimeLog;
use Modules\RestAPI\Http\Requests\BaseRequest;

class IndexRequest extends BaseRequest
{

    /**
     * @return bool
     * @throws \Froiden\RestAPI\Exceptions\UnauthorizedException
     */
    public function authorize()
    {
        $user = api_user();
        return in_array('tasks', $user->modules) && ($user->hasRole('employee'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            //
        ];
    }

    public function messages()
    {
        return [
            //
        ];
    }
}
