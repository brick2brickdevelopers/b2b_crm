<?php

namespace Modules\Sms\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSmsSetting extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $gateway = request()->active_gateway;
        if ($gateway == 'twilio') {
            return [
                'account_sid' => 'required',
                'auth_token' => 'required',
                'from_number' => 'required',
            ];    
        }
        if ($gateway == 'nexmo') {
            return [
                'nexmo_api_key' => 'required',
                'nexmo_api_secret' => 'required',
                'nexmo_from_number' => 'required',
            ];    
        }
        if ($gateway == 'msg91') {
            return [
                'msg91_auth_key' => 'required',
            ];    
        }

        return [
            
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
