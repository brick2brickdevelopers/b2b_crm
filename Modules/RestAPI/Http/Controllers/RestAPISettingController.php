<?php

namespace Modules\RestAPI\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\SuperAdmin\SuperAdminBaseController;
use Edujugon\PushNotification\PushNotification;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Modules\RestAPI\Entities\RestAPISetting;
use Modules\RestAPI\Entities\User;
use Modules\RestAPI\Http\Requests\RestAPISetting\SendPushRequest;
use Modules\RestAPI\Http\Requests\RestAPISetting\UpdateRequest;

class RestAPISettingController extends SuperAdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'restapi::app.menu.restAPISettings';
        $this->pageIcon = 'icon-settings';
        $this->tableClass = 'table table-bordered table-hover table-checkable order-column dataTable no-footer';
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $this->restAPISetting = RestAPISetting::first();
//        $this->iosNotificationWarning = true;
//        $this->iosNotificationKey = env('APN_PEM');
//        $this->iosNotificationFile = file_exists(base_path() . '/'.$this->iosNotificationKey);
//        if ($this->iosNotificationKey && $this->iosNotificationFile) {
//            $this->iosNotificationWarning = false;
//        }
        return view('restapi::super-admin.setting.index', $this->data);
    }

    /**
     * @param UpdateRequest $request
     * @param $id
     * @return array|string[]
     */
    public function update(UpdateRequest $request, $id)
    {
        $restApiSetting = RestAPISetting::find($id);
        $restApiSetting->fcm_key = $request->fcm_key;
        $restApiSetting->save();

        return Reply::redirect(route('super-admin.rest-api-setting.index'), __('messages.settingsUpdated'));
    }

//    public function testPush($platform)
//    {
//        $this->platform = $platform;
//        $this->devices = User::find(user()->id)->devices->where('type', $platform);
//
//        return view('restapi::admin.setting.test-push', $this->data);
//    }
//
//    /**
//     * @param SendPushRequest $request
//     * @return array
//     */
//    public function sendPush(SendPushRequest $request)
//    {
//        $platform = $request->platform === 'ios' ? 'apn' : 'fcm';
//
//        $this->push = new PushNotification($platform);
//        $message = [
//            'apn' => [
//                'aps' => [
//                    'alert' => [
//                        'title' => 'Test notification',
//                        'body' => 'This is test push notification',
//                    ],
//                    'sound' => 'default',
//                    'badge' => 1,
//                    'type' => 'test',
//                ]
//            ],
//            'fcm' => [
//                'data' => [
//                    'title' => 'Test notification',
//                    'body' => 'This is test push notification',
//                    'sound' => 'default',
//                    'badge' => 1,
//                    'type' => 'test',
//                ],
//            ]
//        ];
//        $this->push->setMessage($message[$platform]);
//
//        $setting =  RestAPISetting::first();
//        $fcm_key =  !is_null($setting->fcm_key)? $setting->fcm_key:config('pushnotification.fcm.apiKey');
//        $apn_key =  config('pushnotification.apn.certificate');
//        $this->push->setApiKey($platform === 'apn' ?  $apn_key : $fcm_key);
//
//        $this->push->setDevicesToken($request->device_id)->send();
//
//        return Reply::success(__('restapi::app.notificationSentSuccessfully'));
//    }
}
