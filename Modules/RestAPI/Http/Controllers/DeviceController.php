<?php

namespace Modules\RestAPI\Http\Controllers;

use Froiden\RestAPI\ApiResponse;
use Modules\RestAPI\Entities\Device;
use Modules\RestAPI\Http\Requests\Device\RegisterRequest;
use Modules\RestAPI\Http\Requests\Device\UnregisterRequest;

class DeviceController extends ApiBaseController
{
    protected $model = Device::class;

    protected function register()
    {
        app()->make(RegisterRequest::class);

        // Get device from device_id. If the device exists, we update
        // its registration id, else create new device

        $device = Device::firstOrNew([
            'device_id' => request()->device_id,
        ]);

        $device->device_id = request()->device_id;
        $device->registration_id = request()->registration_id;
        $device->details = request()->details;
        $device->type = request()->type;
        $device->save();

        return ApiResponse::make('Device registered successfully');
    }

    protected function unregister()
    {
        app()->make(UnregisterRequest::class);

        // Get device from device_id. If the device exists, we update
        // its registration id, else create new device

        $device = Device::where('device_id', request()->device_id)->first();

        if ($device) {
            $device->delete();
        }

        return ApiResponse::make('Device unregistered successfully');
    }
}
