<?php

namespace Modules\RestAPI\Http\Requests\Invoice;

use Modules\RestAPI\Entities\Estimate;
use Modules\RestAPI\Entities\Invoice;
use Modules\RestAPI\Http\Requests\BaseRequest;

class ShowRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        $invoice = Invoice::find($this->route('invoice'));

        // Admin can show all estimates
        // Or Client which for whose estimates created
        // Or User who has role other than employee and have permission of view_estimates
        return in_array('invoices', $user->modules) && $invoice && $invoice->visibleTo($user);
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
