<?php

namespace Modules\RestAPI\Http\Requests\Estimate;

use Modules\RestAPI\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();

        // Admin can update estimates
        // Or User who has role other than employee and have permission of edit_estimates
        return in_array('estimates', $user->modules)
            && ($user->hasRole('admin') || ($user->user_other_role !== 'employee' && $user->cans('edit_estimates')));
    }

    public function rules()
    {
        return [
            'client_id' => 'required',
            'valid_till' => 'required',
            'sub_total' => 'required',
            'total' => 'required',
            'currency_id' => 'required'
        ];
    }
}
