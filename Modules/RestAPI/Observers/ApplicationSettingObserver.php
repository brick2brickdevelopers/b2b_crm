<?php

namespace Modules\RestAPI\Observers;

use Modules\RestAPI\Entities\ApplicationSetting;

class ApplicationSettingObserver
{

    public function creating(ApplicationSetting $app)
    {
        // Generate new application id when creating new app
        // this method of generation needs to be modified in future
        $app->app_key = rand(100000000, 999999999);
        $app->authorized_employee_id = user()->id;
    }
}
