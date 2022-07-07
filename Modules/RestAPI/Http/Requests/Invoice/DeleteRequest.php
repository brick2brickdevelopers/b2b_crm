<?php

namespace Modules\RestAPI\Http\Requests\Invoice;

use Modules\RestAPI\Entities\Estimate;
use Modules\RestAPI\Http\Requests\BaseRequest;

class DeleteRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        $firstEstimate = Estimate::latest()->first();

        // We only allow to delete the latest estimate
        // Admin can delete the estimate
        // Or User who has role other than employee and have permission of delete_estimates
        if (in_array('invoices', $user->modules)
            && ($user->hasRole('admin') || ($user->user_other_role !== 'employee' && $user->cans('delete_invoices')))
        ) {
            return $firstEstimate->id == $this->route('estimate');
        } else {
            return false;
        }
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
