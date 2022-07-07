<?php

namespace Modules\RestAPI\Http\Requests\ClientSubCategory;

use Modules\RestAPI\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        return in_array('clients', $user->modules) && ($user->hasRole('admin') || $user->cans('add_clients'));
    }

    public function rules()
    {
        return [
            'category_name' => 'sometimes|required',
            'category.id' => 'required|exists:client_categories,id',
        ];
    }
}
