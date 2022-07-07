<?php

namespace Modules\RestAPI\Http\Requests\UserChat;

use Modules\RestAPI\Entities\Expense;
use Modules\RestAPI\Entities\Ticket;
use Modules\RestAPI\Entities\UserChat;
use Modules\RestAPI\Http\Requests\BaseRequest;

class ShowRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        // Either user has role admin or has permission view_leave
        // Plus he needs to have expenses module enabled from settings
        $userChat = UserChat::find($this->route('message'));
        return in_array('messages', $user->modules) && $userChat && $userChat->visibleTo($user);
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
