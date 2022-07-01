<?php

namespace Modules\RestAPI\Http\Requests\Lead;

use Modules\RestAPI\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest
{

    /**
     * @return bool
     * @throws \Froiden\RestAPI\Exceptions\UnauthorizedException
     */
    public function authorize()
    {
        $user = api_user();
        return in_array('leads', $user->modules) && ($user->hasRole('admin') || $user->cans('edit_lead'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $email = $this->route('lead');
        return [
            'client_name' => 'sometimes|required',
            'client_email' =>
                'sometimes|required|email|unique:leads,client_email,'.$email.'|unique:users,email,'.$email,
            'company_name' => 'sometimes|nullable',
            'website' => 'sometimes|nullable',
            'mobile' => 'sometimes|nullable',
            'lead_source.id' => 'sometimes|nullable',
            'lead_agent.id' => 'sometimes|nullable',
            'lead_status.id' => 'sometimes|nullable',
            'category.id' => 'sometimes|nullable',
            'next_follow_up' => 'sometimes|required|in:yes,no',
        ];
    }

    public function messages()
    {
        return [
            //
        ];
    }
}
