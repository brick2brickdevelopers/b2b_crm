<?php

namespace Modules\RestAPI\Http\Requests\Expense;

use Modules\RestAPI\Http\Requests\BaseRequest;

class IndexRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        return in_array('expenses', $user->modules) && ($user->hasRole('admin') ||  $user->hasRole('employee'));
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
