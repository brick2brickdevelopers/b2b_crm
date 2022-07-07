<?php

namespace Modules\RestAPI\Http\Requests\Ticket;

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
            'subject' => 'required',
            'description' => 'required',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'sometimes|required|in:open,pending,resolved,closed',
            'requester.id' => 'sometimes|required|exists:users,id',
            'agent.id' => 'sometimes|required|exists:users,id'
        ];
    }
}
