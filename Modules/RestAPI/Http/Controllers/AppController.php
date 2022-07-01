<?php

namespace Modules\RestAPI\Http\Controllers;

use App\GlobalSetting;
use Froiden\RestAPI\ApiResponse;

class AppController extends ApiBaseController
{
    public function app()
    {
        $setting = GlobalSetting::select('company_name', 'logo')->first();

        return ApiResponse::make('Application data fetched successfully', $setting->toArray());
    }
}
