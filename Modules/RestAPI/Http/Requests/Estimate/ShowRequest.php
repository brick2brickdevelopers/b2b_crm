<?php

namespace Modules\RestAPI\Http\Requests\Estimate;

use Modules\RestAPI\Entities\Estimate;
use Modules\RestAPI\Http\Requests\BaseRequest;

class ShowRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        $estimate = Estimate::find($this->route('estimate'));

        // Admin can show all estimates
        // Or Client which for whose estimates created
        // Or User who has role other than employee and have permission of view_estimates
        return in_array('estimates', $user->modules) && $estimate && $estimate->visibleTo($user);
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
