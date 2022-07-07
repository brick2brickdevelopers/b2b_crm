<?php

namespace Modules\RestAPI\Http\Requests\LeadSource;

use Modules\RestAPI\Http\Requests\BaseRequest;

class CreateRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        return in_array('leads', $user->modules) && $user->hasRole('admin');
    }

    public function rules()
    {
        return [
            'type' => 'required'
        ];
    }
}
