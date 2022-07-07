<?php

namespace Modules\RestAPI\Http\Requests\LeadCategory;

use Modules\RestAPI\Http\Requests\BaseRequest;

class ShowRequest extends BaseRequest
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
