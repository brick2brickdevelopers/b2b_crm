<?php

namespace Modules\RestAPI\Http\Requests\UserChat;

use Modules\RestAPI\Entities\Expense;
use Modules\RestAPI\Entities\Ticket;
use Modules\RestAPI\Entities\UserChat;
use Modules\RestAPI\Http\Requests\BaseRequest;

class DeleteRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        // Either user has role admin or has permission delete_notice
        // Plus he needs to have notices module enabled from settings
        $userChat = UserChat::findOrFail($this->route('message'));

        return in_array('messages', $user->modules)
            && $userChat && ($user->hasRole('admin') || $user->hasRole('employee'));
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
