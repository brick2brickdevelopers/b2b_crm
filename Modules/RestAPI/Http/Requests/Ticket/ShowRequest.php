<?php

namespace Modules\RestAPI\Http\Requests\Ticket;

use Modules\RestAPI\Entities\Expense;
use Modules\RestAPI\Entities\Ticket;
use Modules\RestAPI\Http\Requests\BaseRequest;

class ShowRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        // Either user has role admin or has permission view_leave
        // Plus he needs to have expenses module enabled from settings
        $ticket = Ticket::find($this->route('ticket'));
        return in_array('tickets', $user->modules) && $ticket && $ticket->visibleTo($user);
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
