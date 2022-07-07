<?php

namespace Modules\RestAPI\Http\Requests\UserChat;

use Modules\RestAPI\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        // Either user has role admin or has permission edit_notice
        // Plus he needs to have notices module enabled from settings
        return in_array('messages', $user->modules) && ($user->hasRole('admin') || $user->hasRole('employee'));
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
