<?php

namespace Modules\Sms\Listeners;

use App\Events\NewProjectMemberEvent;
use App\Events\ProjectReminderEvent;
use App\Notifications\ProjectReminder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;
use Modules\Sms\Entities\SmsSetting;
use Modules\Sms\Notifications\NewProjectMemberSms;
use Modules\Sms\Notifications\ProjectReminderSms;

class SmsProjectReminderListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->smsSettings = sms_setting();
        Config::set('twilio-notification-channel.auth_token', $this->smsSettings->auth_token);
        Config::set('twilio-notification-channel.account_sid', $this->smsSettings->account_sid);
        Config::set('twilio-notification-channel.from', $this->smsSettings->from_number);

        Config::set('nexmo.api_key', $this->smsSettings->nexmo_api_key);
        Config::set('nexmo.api_secret', $this->smsSettings->nexmo_api_secret);
        Config::set('services.nexmo.sms_from', $this->smsSettings->nexmo_from_number);

        Config::set('services.msg91.key', $this->smsSettings->msg91_auth_key);
        Config::set('services.msg91.msg91_from', $this->smsSettings->msg91_from);
    }

    /**
     * Handle the event.
     *
     * @param  ProjectReminderEvent $event
     * @return void
     */
    public function handle(ProjectReminderEvent $event)
    {
        Notification::send($event->notifyUser, new ProjectReminderSms($event->projectsArr, [
            'company' => $event->projectData['company'], 'project_setting' => $event->projectData['project_setting'],
        ]));
    }
}
