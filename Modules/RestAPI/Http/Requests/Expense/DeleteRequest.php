<?php

namespace Modules\RestAPI\Http\Requests\Expense;

use Modules\RestAPI\Entities\Expense;
use Modules\RestAPI\Http\Requests\BaseRequest;

class DeleteRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        // Either user has role admin or has permission delete_notice
        // Plus he needs to have notices module enabled from settings
        $expense = Expense::findOrFail($this->route('expense'));

        return in_array('expenses', $user->modules)
            && ($user->hasRole('admin') || ($user->cans('delete_expense') && $expense->status === 'pending')) ;
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
