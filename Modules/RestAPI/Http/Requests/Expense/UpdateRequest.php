<?php

namespace Modules\RestAPI\Http\Requests\Expense;

use Modules\RestAPI\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        // Either user has role admin or has permission edit_notice
        // Plus he needs to have notices module enabled from settings
        return in_array('expenses', $user->modules) && ($user->hasRole('admin') || $user->cans('edit_expense'));
    }

    public function rules()
    {
        return [
            'user.id' => 'sometimes|required|exists:users,id',
            'project.id' => 'sometimes|exists:projects,id',
            'item_name' => 'sometimes|required',
            'purchase_date' => 'sometimes|required',
            'price' => 'sometimes|required|numeric',
            'status' => 'sometimes|required|in:pending,approved,rejected'
        ];
    }
}
