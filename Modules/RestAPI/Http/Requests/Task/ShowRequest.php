<?php

namespace Modules\RestAPI\Http\Requests\Task;

use Modules\RestAPI\Entities\Task;
use Modules\RestAPI\Http\Requests\BaseRequest;

class ShowRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();

        // Either user has role admin or has permission view_projects
        // Plus he needs to have projects module enabled from settings

        $task = Task::find($this->route('task'));

        return in_array('tasks', $user->modules) && $task && $task->visibleTo($user);
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
