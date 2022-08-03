<?php

namespace Modules\RestAPI\Http\Requests\Event;

use App\Rules\CheckAfterDate;
use App\Rules\CheckDateFormat;
use App\Rules\CheckEqualAfterDate;
use Illuminate\Support\Facades\Auth;
use Modules\RestAPI\Http\Requests\BaseRequest;

class CreateRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        return in_array('events', $user->modules)
            && ($user->hasRole('admin') || ($user->user_other_role !== 'employee' && $user->cans('add_events')));
    }

    public function rules()
    {

        $startDateTime = $this->start_date . ' ' . $this->start_time;
        $endDateTime = $this->end_date . ' ' . $this->end_time;
        return [
            'event_name' => 'required',
            'start_date_time' => 'required',
            'end_date_time' => 'required',
            'event_name' => 'required',
            'description' => 'required',
            'where' => 'required',
        ];
    }

    public function messages()
    {
        return [
           
        ];
    }
}
