<?php

namespace Modules\RestAPI\Http\Requests\Auth;

use Modules\RestAPI\Http\Requests\BaseRequest;

class RefreshTokenRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
//            'token' => 'required'
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
