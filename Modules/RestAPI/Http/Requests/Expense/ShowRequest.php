<?php

namespace Modules\RestAPI\Http\Requests\Expense;

use Modules\RestAPI\Entities\Expense;
use Modules\RestAPI\Http\Requests\BaseRequest;

class ShowRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        // Either user has role admin or has permission view_leave
        // Plus he needs to have expenses module enabled from settings
        $expense = Expense::find($this->route('expense'));
        return in_array('expenses', $user->modules) && $expense && $expense->visibleTo($user);
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
