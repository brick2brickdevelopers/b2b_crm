<?php namespace Modules\RestAPI\Http\Requests\Projects;

use Modules\RestAPI\Entities\Project;
use Modules\RestAPI\Entities\Task;
use Modules\RestAPI\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest
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
        $rules = [
            'project_name' => 'sometimes|required',
            'start_date' => 'sometimes|required',
            'hours_allocated' => 'nullable|numeric',
        ];

        if (!$this->has('without_deadline')) {
            $rules['deadline'] = 'sometimes|required';
        }
        if ($this->project_budget != '') {
            $rules['project_budget'] = 'numeric';
            $rules['currency.id'] = 'sometimes|exists:currencies,id';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            //
        ];
    }
}
