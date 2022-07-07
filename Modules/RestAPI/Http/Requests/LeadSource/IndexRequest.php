<?php

namespace Modules\RestAPI\Http\Requests\LeadSource;

use Modules\RestAPI\Http\Requests\BaseRequest;

class IndexRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        return in_array('leads', $user->modules) && ($user->hasRole('admin') || $user->cans('view_lead'));
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
