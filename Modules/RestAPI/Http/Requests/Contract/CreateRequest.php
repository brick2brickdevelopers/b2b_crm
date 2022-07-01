<?php

namespace Modules\RestAPI\Http\Requests\Contract;

use Modules\RestAPI\Http\Requests\BaseRequest;

class CreateRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        return in_array('contracts', $user->modules) && ($user->hasRole('admin') || $user->cans('create_contract'));
    }

    public function rules()
    {
        return [
            'client.id' => 'required|exists:users,id',
            'subject' => 'required',
            'amount' => 'required',
            'contract_type.id' => 'required|exists:contract_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ];
    }
}
