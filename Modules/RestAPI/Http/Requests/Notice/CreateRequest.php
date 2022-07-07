<?php

namespace Modules\RestAPI\Http\Requests\Notice;

use Modules\RestAPI\Http\Requests\BaseRequest;

class CreateRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        // Either user has role admin or has permission edit_notice
        // Plus he needs to have notices module enabled from settings
        return in_array('notices', $user->modules) && ($user->hasRole('admin') || $user->cans('add_notice'));
    }

    public function rules()
    {
        return [
            'heading' => 'required',
            'to' => 'required|in:employee,client',
        ];
    }
}
