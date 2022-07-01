<?php

namespace Modules\Sms\Listeners;

use App\Events\TicketEvent;
use App\User;
use Illuminate\Support\Facades\Notification;
use Modules\Sms\Notifications\NewTicketSms;
use Modules\Sms\Notifications\TicketAgentSms;
use Illuminate\Support\Facades\Config;
use Modules\Sms\Entities\SmsSetting;

class SmsTicketListener
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
     * @param  TicketEvent $event
     * @return void
     */
    public function handle(TicketEvent $event)
    {
        if ($event->notificationName == 'NewTicket') {
            Notification::send(User::allAdmins(),  new NewTicketSms($event->ticket));
        } elseif ($event->notificationName == 'TicketAgent') {
            Notification::send($event->ticket->agent, new TicketAgentSms($event->ticket));
        }
    }
}
