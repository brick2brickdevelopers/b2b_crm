<?php

namespace Modules\RestAPI\Http\Requests\TicketReply;

use Modules\RestAPI\Entities\Expense;
use Modules\RestAPI\Entities\Ticket;
use Modules\RestAPI\Entities\TicketReply;
use Modules\RestAPI\Http\Requests\BaseRequest;

class DeleteRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        // Either user has role admin or has permission delete_notice
        // Plus he needs to have notices module enabled from settings
        $ticketReply = TicketReply::findOrFail($this->route('ticket_reply'));

        return in_array('tickets', $user->modules)
            && $ticketReply && ($user->hasRole('admin') || $user->cans('delete_ticket'));
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
