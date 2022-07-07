<?php

namespace Modules\RestAPI\Http\Requests\LeadCategory;

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
            'category_name' => 'required'
        ];
    }
}
