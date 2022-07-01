<?php

namespace Modules\RestAPI\Http\Requests\Ticket;

use Modules\RestAPI\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        // Either user has role admin or has permission edit_notice
        // Plus he needs to have notices module enabled from settings
        return in_array('tickets', $user->modules) && ($user->hasRole('admin') || $user->cans('edit_ticket'));
    }

    public function rules()
    {
        return [
            'subject' => 'sometimes|required|min:5',
            'description' => 'sometimes|required|min:5',
            'priority' => 'sometimes|required|in:low,medium,high,urgent',
            'status' => 'sometimes|required|in:open,pending,resolved,closed',
            'user.id' => 'sometimes|required|exists:users,id'
        ];
    }
}
