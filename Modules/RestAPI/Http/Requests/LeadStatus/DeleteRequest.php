<?php

namespace Modules\RestAPI\Http\Requests\LeadStatus;

use Modules\RestAPI\Http\Requests\BaseRequest;

class DeleteRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        return in_array('leads', $user->modules) && $user->hasRole('admin');
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
