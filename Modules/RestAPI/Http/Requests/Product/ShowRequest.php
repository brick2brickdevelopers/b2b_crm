<?php

namespace Modules\RestAPI\Http\Requests\Product;

use Modules\RestAPI\Http\Requests\BaseRequest;

class ShowRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        // Either user has role admin or has permission view_product
        // Plus he needs to have products module enabled from settings
        return in_array('products', $user->modules) && ($user->hasRole('admin') || $user->cans('view_product'));
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
