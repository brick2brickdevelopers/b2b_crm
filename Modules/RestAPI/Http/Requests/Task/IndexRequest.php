<?php

namespace Modules\RestAPI\Http\Requests\Task;

use Modules\RestAPI\Http\Requests\BaseRequest;

class IndexRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();

        // Either user has role admin or has permission view_projects
        // Plus he needs to have projects module enabled from settings

        return in_array('tasks', $user->modules);
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
