<?php

namespace Modules\Sms\Http\Traits;


trait SendNexmoMessage
{

    public function sendNexmo($notifiable, $message)
    {
        $settings = sms_setting();

        $toNumber = $notifiable->country->phonecode.$notifiable->mobile;
        $fromNumber = $settings->nexmo_from_number;

        $nexmo = app('Nexmo\Client');
        $nexmo->message()->send([
            'to'   => $toNumber,
            'from' => $fromNumber,
            'text' => $message
        ]);
    }
}
