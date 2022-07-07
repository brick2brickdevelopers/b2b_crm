<?php namespace Modules\RestAPI\Http\Requests\Projects;

use Modules\RestAPI\Http\Requests\BaseRequest;

class DeleteRequest extends BaseRequest
{

    /**
     * @return bool
     * @throws \Froiden\RestAPI\Exceptions\UnauthorizedException
     */
    public function authorize()
    {
        $user = api_user();
        // Either user has role admin or has permission delete_projects
        // Plus he needs to have projects module enabled from settings
        return in_array('projects', $user->modules) && ($user->hasRole('admin') || $user->cans('delete_projects'));
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
