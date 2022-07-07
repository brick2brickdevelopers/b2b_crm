<?php namespace Modules\RestAPI\Http\Requests\Projects;

use Modules\RestAPI\Entities\Project;
use Modules\RestAPI\Http\Requests\BaseRequest;

class ShowRequest extends BaseRequest
{

    /**
     * @return bool
     * @throws \Froiden\RestAPI\Exceptions\UnauthorizedException
     */
    public function authorize()
    {
        $user = api_user();
        // Either user has role admin or has permission view_projects
        // Plus he needs to have projects module enabled from settings
//      return in_array('projects', $user->modules) && ($user->hasRole('admin') || $user->can('view_projects'));
//        dd($user);
        $project = Project::find($this->route('project'));

        return in_array('projects', $user->modules) && $project && $project->visibleTo($user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

        ];
    }
}
