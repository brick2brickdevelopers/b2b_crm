<?php

namespace Modules\Sms\Listeners;

use App\Events\TaskEvent;
use Illuminate\Support\Facades\Config;
use Modules\Sms\Entities\SmsSetting;
use Illuminate\Support\Facades\Notification;
use Modules\Sms\Notifications\NewClientTaskSms;
use Modules\Sms\Notifications\NewTaskSms;
use Modules\Sms\Notifications\TaskCompletedSms;
use Modules\Sms\Notifications\TaskUpdatedClientSms;
use Modules\Sms\Notifications\TaskUpdatedSms;

class SmsTaskListener
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
     * @param object $event
     * @return void
     */
    public function handle(TaskEvent $event)
    {
        if ($event->notificationName == 'NewClientTask') {
            Notification::send($event->notifyUser, new NewClientTaskSms($event->task));
        } elseif ($event->notificationName == 'NewTask') {
            Notification::send($event->notifyUser, new NewTaskSms($event->task));
        } elseif ($event->notificationName == 'TaskUpdated') {
            Notification::send($event->notifyUser, new TaskUpdatedSms($event->task));
        } elseif ($event->notificationName == 'TaskCompleted') {
            Notification::send($event->notifyUser, new TaskCompletedSms($event->task));
        } elseif ($event->notificationName == 'TaskUpdatedClient') {
            Notification::send($event->notifyUser, new TaskUpdatedClientSms($event->task));
        }
    }
}
