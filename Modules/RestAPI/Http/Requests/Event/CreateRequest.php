<?php

namespace Modules\RestAPI\Http\Requests\Event;

use App\Rules\CheckAfterDate;
use App\Rules\CheckDateFormat;
use App\Rules\CheckEqualAfterDate;
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
            // 'event_name' => 'required',
            // // 'start_date' => 'required',
            // // 'end_date' => ['required', new CheckDateFormat(null, company()->date_format), new CheckEqualAfterDate('start_date', company()->date_format)],
            // // 'start_time' => 'required',
            // // 'end_time' => ['required', new CheckAfterDate('', company()->date_format . ' ' . company()->time_format, $startDateTime, null, $endDateTime)],
            // // 'all_employees' => 'sometimes',
            // // 'user_id.0' => 'required_unless:all_employees,true',
            // // 'where' => 'required',
            // 'created_by' => 'required',
            // // 'description' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'user_id.0.required_unless' => __('messages.atleastOneValidation'),
            'end_time.after' => __('messages.endTimeGreaterThenStart')
        ];
    }
}
