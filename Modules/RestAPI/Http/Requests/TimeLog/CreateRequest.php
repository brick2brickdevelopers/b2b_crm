<?php

namespace Modules\RestAPI\Http\Requests\TimeLog;

use Froiden\RestAPI\Exceptions\ApiException;
use Modules\RestAPI\Entities\ProjectTimeLog;
use Modules\RestAPI\Http\Requests\BaseRequest;

class CreateRequest extends BaseRequest
{

    /**
     * @return bool
     * @throws \Froiden\RestAPI\Exceptions\UnauthorizedException
     */
    public function authorize()
    {
        $user = api_user();
        $activeTimer = ProjectTimeLog::with('user')
            ->whereNull('end_time')
            ->join('users', 'users.id', '=', 'project_time_logs.user_id')
            ->where('user_id', $user->id)->first();
        if ($activeTimer) {
            throw new ApiException(__('messages.timerAlreadyRunning'), null, 422, 422, 2001);
        }
        return in_array('tasks', $user->modules) && ($user->hasRole('employee'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'project.id' => 'sometimes|exists:projects,id',
            'task.id' => 'required|exists:tasks,id',
            'memo' => 'required',
        ];
    }

    public function messages()
    {
        return [
            //
        ];
    }
}
