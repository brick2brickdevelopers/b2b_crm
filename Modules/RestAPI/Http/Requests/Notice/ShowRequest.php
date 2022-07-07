<?php

namespace Modules\RestAPI\Http\Requests\Notice;

use Modules\RestAPI\Entities\Notice;
use Modules\RestAPI\Http\Requests\BaseRequest;

class ShowRequest extends BaseRequest
{

    public function authorize()
    {
        $user = api_user();
        // Either user has role admin or has permission view_notice
        // Plus he needs to have notices module enabled from settings
        $notice = Notice::find($this->route('notice'));
        return in_array('notices', $user->modules) && $notice && $notice->visibleTo($user);
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
