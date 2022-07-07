<?php

namespace Modules\Sms\Notifications;

use App\EmailNotificationSetting;
use App\Invoice;
use Craftsys\Notifications\Messages\Msg91SMS;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Sms\Http\Traits\WhatsappMessageTrait;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;
use Illuminate\Notifications\Messages\NexmoMessage;
use Modules\Sms\Http\Traits\SendNexmoMessage;

class NewInvoiceSms extends Notification
{
    use Queueable, WhatsappMessageTrait, SendNexmoMessage;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;

        $this->emailSetting = EmailNotificationSetting::where('setting_name', 'Invoice Create/Update Notification')->first();
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
        if ($this->emailSetting->send_twilio == 'yes' && !is_null($notifiable->mobile) && !is_null($notifiable->country_id)) {
            if (sms_setting()->status) {
                array_push($via, TwilioChannel::class);
            }
            if (sms_setting()->nexmo_status) {
                $message = __('email.invoice.subject') . ' - ' . $this->invoice->invoice_number;
                
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
        $message = __('email.invoice.subject') . ' - ' . $this->invoice->invoice_number;
        $this->toWhatsapp($notifiable, $message);

        if (sms_setting()->status) {
            return (new TwilioSmsMessage())
                ->content($message);
        }
    }

    public function toNexmo($notifiable)
    {
        $message = __('email.invoice.subject') . ' - ' . $this->invoice->invoice_number;
        
        if (sms_setting()->nexmo_status) {
            return (new NexmoMessage())
                ->content($message);
        }
    }

    public function toMsg91($notifiable)
    {
        $message = __('email.invoice.subject') . ' - ' . $this->invoice->invoice_number;
        
        if (sms_setting()->msg91_status) {
            return (new \Craftsys\Notifications\Messages\Msg91SMS)
                ->content($message);
        }
    }

}
