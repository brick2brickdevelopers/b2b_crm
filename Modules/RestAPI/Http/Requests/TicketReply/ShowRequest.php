<?php

namespace Modules\RestAPI\Http\Requests\TicketReply;

use Modules\RestAPI\Entities\TicketReply;
use Modules\RestAPI\Http\Requests\BaseRequest;

class ShowRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        // Either user has role admin or has permission view_leave
        // Plus he needs to have expenses module enabled from settings
        $ticketReply = TicketReply::find($this->route('ticket_reply'));
        return in_array('tickets', $user->modules) && $ticketReply && $ticketReply->visibleTo($user);
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
