<?php

namespace Modules\RestAPI\Http\Requests\Ticket;

use Modules\RestAPI\Entities\Expense;
use Modules\RestAPI\Entities\Ticket;
use Modules\RestAPI\Http\Requests\BaseRequest;

class DeleteRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        // Either user has role admin or has permission delete_notice
        // Plus he needs to have notices module enabled from settings
        $ticket = Ticket::findOrFail($this->route('ticket'));

        return in_array('tickets', $user->modules)
            && $ticket && ($user->hasRole('admin') || $user->cans('delete_ticket') );
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
