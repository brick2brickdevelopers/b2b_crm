<?php

namespace Modules\RestAPI\Http\Requests\TicketReply;

use Modules\RestAPI\Http\Requests\BaseRequest;

class CreateRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        return in_array('tickets', $user->modules) && ($user->hasRole('admin') || $user->hasRole('employee'));
    }

    public function rules()
    {
        return [
//            'message' => 'required_without:fileData',
            'ticket.id' => 'required|exists:tickets,id',
            'user.id' => 'required|exists:users,id'
        ];
    }
}
