<?php

namespace Modules\RestAPI\Http\Requests\Task;

use Modules\RestAPI\Http\Requests\BaseRequest;

class CreateRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();

        // Either user has role admin or has permission view_projects
        // Plus he needs to have projects module enabled from settings
        return in_array('tasks', $user->modules) && ($user->hasRole('admin') || $user->hasRole('employee'));
    }

    public function rules()
    {
        return [
            'task_users.*.id' => 'required|exists:users,id',
            'project.id' => 'sometimes|exists:projects,id',
            'heading' => 'required',
            'start_date' => 'sometimes|nullable|date',
            'priority' =>'required|in:medium,high,low',
            'due_date' => 'required|date',
            'category.id' => 'sometimes|exists:task_category,id',
            'is_private' =>'boolean',
            'status' =>'required|in:completed,incomplete',
        ];
    }
}
