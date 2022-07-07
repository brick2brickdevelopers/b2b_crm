<?php

namespace Modules\RestAPI\Http\Requests\Tax;

use Modules\RestAPI\Http\Requests\BaseRequest;

class IndexRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();

        // Either user has role admin or has permission view_projects
        // Plus he needs to have projects module enabled from settings

        return isset($user);
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
