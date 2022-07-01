<?php

namespace Modules\RestAPI\Http\Requests\Leave;

use Modules\RestAPI\Entities\Leave;
use Modules\RestAPI\Http\Requests\BaseRequest;

class DeleteRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        // Either user has role admin or has permission delete_notice
        // Plus he needs to have notices module enabled from settings
        $expense = Leave::findOrFail($this->route('leave'));
        return in_array('leaves', $user->modules)
            && ($user->hasRole('admin') || ($user->cans('delete_leave') && $expense->status === 'pending'));
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
