<?php

namespace Modules\RestAPI\Http\Requests\TicketChannel;

use Modules\RestAPI\Http\Requests\BaseRequest;

class IndexRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        return in_array('tickets', $user->modules) && ($user->hasRole('admin') ||  $user->hasRole('employee'));
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
