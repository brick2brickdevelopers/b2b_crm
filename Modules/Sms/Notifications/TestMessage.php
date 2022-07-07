<?php

namespace Modules\Sms\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\NexmoMessage;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;

class TestMessage extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $request = request();

        $via = array();
        if (sms_setting()->status) { 
            $number = $request->phone_code . $request->mobile;
            $notifiable->phone_number = $number;
            array_push($via, TwilioChannel::class);
        }
        if (sms_setting()->nexmo_status) {
            $number = str_replace('+', '', $request->phone_code) . $request->mobile;
            $notifiable->phone_number = $number;

            $nexmo = app('Nexmo\Client');
            $nexmo->message()->send([
                'to'   => $number,
                'from' => sms_setting()->nexmo_from_number,
                'text' => 'This is nexmo test message'
            ]);

            array_push($via, 'nexmo');
        }
        if (sms_setting()->msg91_status) {
            $number = str_replace('+', '', $request->phone_code) . $request->mobile;
            $notifiable->phone_number = $number;
            array_push($via, 'msg91');
        }
        
        return $via;
    }

    public function toTwilio($notifiable)
    {
        $settings = sms_setting();
        $message = "This is twilio test message";
        // $this->toWhatsapp($notifiable, $message);
        if ($settings->whatsapp_status) {
            $toNumber = request()->phone_code . request()->mobile;
            $fromNumber = $settings->whatapp_from_number;
            $twilio = new \Twilio\Rest\Client($settings->account_sid, $settings->auth_token);
            $twilio->messages
                ->create(
                    "whatsapp:$toNumber", // to 
                    array(
                        "from" => "whatsapp:$fromNumber",
                        "body" => $message
                    )
                );
        }

        if ($settings->status) {
            return (new TwilioSmsMessage())
                ->content($message);
        }
    }

    public function toNexmo($notifiable)
    {
        $message = "This is nexmo test message";

        if (sms_setting()->nexmo_status) {
            return (new NexmoMessage())
                ->content($message);
        }
    }

    public function toMsg91($notifiable)
    {
        $message = "This is msg91 test message";
        
        if (sms_setting()->msg91_status) {
            return (new \Craftsys\Notifications\Messages\Msg91SMS)
                ->from(sms_setting()->msg91_from)
                ->content($message);
        }
    }
}
