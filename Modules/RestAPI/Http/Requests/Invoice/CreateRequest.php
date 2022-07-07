<?php

namespace Modules\RestAPI\Http\Requests\Invoice;

use Modules\RestAPI\Http\Requests\BaseRequest;

class CreateRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();

        // Admin can add estimates
        // Or User who has role other than employee and have permission of add_estimates
        return in_array('invoices', $user->modules)
            && ($user->hasRole('admin') || ($user->user_other_role !== 'employee' && $user->cans('add_invoices')));
    }

    public function rules()
    {
        $rules = [
            'invoice_number' => 'required',
            'issue_date' => 'required',
            'due_date' => 'required',
            'sub_total' => 'required',
            'total' => 'required',
            'currency_id' => 'required'
        ];

        if ($this->project_id == '') {
            $rules['client_id'] = 'required';
        }

        if ($this->recurring_payment == 'yes') {
            $rules['billing_frequency'] = 'required';
            $rules['billing_interval'] = 'required|integer';
            $rules['billing_cycle'] = 'required|integer';
        }

        return $rules;
    }
}
