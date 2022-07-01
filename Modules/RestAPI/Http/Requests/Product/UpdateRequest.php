<?php

namespace Modules\RestAPI\Http\Requests\Product;

use Modules\RestAPI\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        // Either user has role admin or has permission edit_product
        // Plus he needs to have products module enabled from settings
        return in_array('products', $user->modules) && ($user->hasRole('admin') || $user->cans('edit_product'));
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|required',
            'price' => 'sometimes|required|numeric',
            'description' => 'sometimes|required',
            'purchase_allow' => 'sometimes|boolean',
            'taxes' => 'json',
        ];
    }
}
