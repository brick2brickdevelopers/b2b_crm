<?php

namespace Modules\Sms\Http\Controllers;

use App\Country;
use App\EmailNotificationSetting;
use App\Helper\Reply;
use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Controllers\SuperAdmin\SuperAdminBaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Modules\Sms\Entities\SmsSetting;
use Illuminate\Support\Facades\Notification;
use Modules\Sms\Http\Requests\StoreSmsSetting;
use Modules\Sms\Notifications\TestMessage;

class SuperAdminSmsSettingsController extends SuperAdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle =  'app.menu.settings';
        $this->pageIcon = 'icon-settings';
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $this->smsSetting = sms_setting();
        $this->countries = Country::all();
        $this->emailSetting = EmailNotificationSetting::where('setting_name', '<>', 'Expense Status Changed')
            ->where('setting_name', '<>', 'User Registration/Added by Admin')
            ->where('setting_name', '<>', 'Discussion Reply')
            ->get();
        return view('sms::sms.create', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(StoreSmsSetting $request)
    {
        $smsSetting = SmsSetting::first();
        if ($request->active_gateway == "twilio") {
            $smsSetting->account_sid = $request->account_sid;
            $smsSetting->auth_token = $request->auth_token;
            $smsSetting->from_number = $request->from_number;
            $smsSetting->whatapp_from_number = $request->whatapp_from_number;
            $smsSetting->status = 1;
            $smsSetting->whatsapp_status = $request->whatsapp_status;
            $smsSetting->nexmo_status = 0;
        }

        if ($request->active_gateway == "nexmo") {
            $smsSetting->nexmo_api_key = $request->nexmo_api_key;
            $smsSetting->nexmo_api_secret = $request->nexmo_api_secret;
            $smsSetting->nexmo_from_number = $request->nexmo_from_number;
            $smsSetting->status = 0;
            $smsSetting->whatsapp_status = 0;
            $smsSetting->nexmo_status = 1;
        }

        if ($request->active_gateway == "msg91") {
            $smsSetting->msg91_auth_key = $request->msg91_auth_key;
            $smsSetting->msg91_from = $request->msg91_from;
            $smsSetting->status = 0;
            $smsSetting->whatsapp_status = 0;
            $smsSetting->nexmo_status = 0;
            $smsSetting->msg91_status = 1;
        }

        if ($request->active_gateway == '') {
            $smsSetting->status = 0;
            $smsSetting->whatsapp_status = 0;
            $smsSetting->nexmo_status = 0;
            $smsSetting->msg91_status = 0;
        }

        $smsSetting->save();
        session(['sms_setting' => SmsSetting::first()]);
        return Reply::success(__('messages.settingsUpdated'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('twilio::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('twilio::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $setting = EmailNotificationSetting::findOrFail($request->id);
        $setting->send_twilio = $request->send_email;
        $setting->save();

        session(['email_notification_setting' => EmailNotificationSetting::all()]);

        return Reply::success(__('messages.settingsUpdated'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function sendTestMessage(Request $request)
    {
        $request->validate([
            'mobile' => 'required|integer',
        ]);

        $this->smsSettings = sms_setting();

        if (!$this->smsSettings->status && !$this->smsSettings->nexmo_status && !$this->smsSettings->msg91_status) {
            return Reply::error(__('sms::modules.noGatewayEnabled'));
        }

        Config::set('twilio-notification-channel.auth_token', $this->smsSettings->auth_token);
        Config::set('twilio-notification-channel.account_sid', $this->smsSettings->account_sid);
        Config::set('twilio-notification-channel.from', $this->smsSettings->from_number);

        Config::set('nexmo.api_key', $this->smsSettings->nexmo_api_key);
        Config::set('nexmo.api_secret', $this->smsSettings->nexmo_api_secret);
        Config::set('services.nexmo.sms_from', $this->smsSettings->nexmo_from_number);
        Config::set('services.msg91.key', $this->smsSettings->msg91_auth_key);
        Config::set('services.msg91.msg91_from', $this->smsSettings->msg91_from);

        $number = $request->phone_code . $request->mobile;
        $nexmoNumber = str_replace('+', '', $request->phone_code) . $request->mobile;
        $msg91Number = str_replace('+', '', $request->phone_code) . $request->mobile;

  
        Notification::route('msg91', $msg91Number)->notify(new TestMessage());
        return Reply::success('Test message sent successfully');
    }

    public function twilioLookUp($number)
    {
        $this->smsSettings = sms_setting();
        $sid    = $this->smsSettings->account_sid;
        $token  = $this->smsSettings->auth_token;
        $twilio = new \Twilio\Rest\Client($sid, $token);
        return $twilio->lookups->v1->phoneNumbers($number)->fetch();
    }
}
