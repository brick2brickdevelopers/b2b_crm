<?php

namespace Modules\RestAPI\Http\Requests\Invoice;

use Modules\RestAPI\Http\Requests\BaseRequest;

class IndexRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();

        // Admin can view the estimates
        // Or Client with his/her estimates
        // Or User who has role other than employee and have permission of view_estimates
        return in_array('invoices', $user->modules)
            && (
                $user->hasRole('admin')
                || $user->hasRole('client')
                || ($user->user_other_role !== 'employee' && $user->cans('view_invoices'))
            );
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
