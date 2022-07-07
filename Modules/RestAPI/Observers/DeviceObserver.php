<?php

namespace Modules\RestAPI\Observers;

use Modules\RestAPI\Entities\Device;

class DeviceObserver
{

    public function saving(Device $device)
    {
        $user =  api_user();

        if ($user) {
            $device->user_id = $user->id;
        }
    }
}
