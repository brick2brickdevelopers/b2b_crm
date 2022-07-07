<?php

namespace Modules\RestAPI\Http\Requests\Expense;

use Modules\RestAPI\Http\Requests\BaseRequest;

class CreateRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        return in_array('expenses', $user->modules) && ($user->hasRole('admin') || $user->hasRole('employee'));
    }

    public function rules()
    {
        return [
            'user.id' => 'required|exists:users,id',
            'project.id' => 'sometimes|exists:projects,id',
            'currency.id' => 'required|exists:currencies,id',
            'item_name' => 'required',
            'purchase_date' => 'required',
            'price' => 'required|numeric',
            'status' => 'sometimes|required|in:pending,approved,rejected'
        ];
    }
}
