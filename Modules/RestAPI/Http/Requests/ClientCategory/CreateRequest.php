<?php

namespace Modules\RestAPI\Http\Requests\ClientCategory;

use Modules\RestAPI\Http\Requests\BaseRequest;

class CreateRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        return in_array('clients', $user->modules) && ($user->hasRole('admin') || $user->cans('add_clients'));
    }

    public function rules()
    {
        return [
            'category_name' => 'required'
        ];
    }
}
