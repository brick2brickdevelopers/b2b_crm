<?php

namespace Modules\Sms\Notifications;

use App\Company;
use App\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notification;
use Modules\Sms\Http\Traits\SendNexmoMessage;
use Modules\Sms\Http\Traits\WhatsappMessageTrait;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;

class EventInviteSms extends Notification
{
    use Queueable, WhatsappMessageTrait, SendNexmoMessage;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $event;
    private $global;
    private $smsSettings;
    public function __construct(Event $event)
    {
        $this->event = $event;

        $this->global    = Company::findOrFail(company()->id);
        $this->smsSettings = sms_setting();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $via = array();
        if (!is_null($notifiable->mobile) && !is_null($notifiable->country_id)) {
            if (sms_setting()->status) {
                array_push($via, TwilioChannel::class);
            }
            if (sms_setting()->nexmo_status) {
                $message = __('email.newEvent.subject') . "\n" . __('modules.events.eventName') . ': ' . $this->event->event_name . "\n" . __('modules.events.startOn') . ': ' . $this->event->start_date_time->format($this->global->date_format . ' - ' . $this->global->time_format);
                
                $this->sendNexmo($notifiable, $message);
    
                array_push($via, 'nexmo');
            }
            if (sms_setting()->msg91_status) {
                array_push($via, 'msg91');
            }
        }
        return $via;
    }

    public function toTwilio($notifiable)
    {
        $message = __('email.newEvent.subject') . "\n" . __('modules.events.eventName') . ': ' . $this->event->event_name . "\n" . __('modules.events.startOn') . ': ' . $this->event->start_date_time->format($this->global->date_format . ' - ' . $this->global->time_format);
        $this->toWhatsapp($notifiable, $message);

        if ($this->smsSettings->status) {
            return (new TwilioSmsMessage())
                ->content($message);
        }
    }

    public function toNexmo($notifiable)
    {
        $message = __('email.newEvent.subject') . "\n" . __('modules.events.eventName') . ': ' . $this->event->event_name . "\n" . __('modules.events.startOn') . ': ' . $this->event->start_date_time->format($this->global->date_format . ' - ' . $this->global->time_format);
        
        if (sms_setting()->nexmo_status) {
            return (new NexmoMessage())
                ->content($message);
        }
    }

    public function toMsg91($notifiable)
    {
        $message = __('email.newEvent.subject') . "\n" . __('modules.events.eventName') . ': ' . $this->event->event_name . "\n" . __('modules.events.startOn') . ': ' . $this->event->start_date_time->format($this->global->date_format . ' - ' . $this->global->time_format);
        
        if (sms_setting()->msg91_status) {
            return (new \Craftsys\Notifications\Messages\Msg91SMS)
                ->from(sms_setting()->msg91_from)
                ->content($message);
        }
    }

    
}
