<?php

namespace Modules\Asset\Notifications;

use App\Traits\SmtpSettings;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Asset\Entities\Asset;
use Modules\Asset\Entities\AssetHistory;
use Modules\Payroll\Entities\SalarySlip;

class AssetLent extends Notification implements ShouldQueue
{
    use Queueable, SmtpSettings;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $asset;
    private $history;
    public function __construct(Asset $asset, AssetHistory $history)
    {
        $this->asset = $asset;
        $this->history = $history;
        $this->setMailConfigs();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('asset::app.assetLent'))
            ->greeting(__('email.hello') . ' ' . ucwords($notifiable->name) . '!')
            ->line(__('asset::app.assetLentMessageForMail'))
            ->line(__('asset::app.assetName').': '. $this->asset->name)
            ->line(__('asset::app.dateGiven').': '. $this->history->date_given->format('d F Y H:i A'))
            ->line(__('asset::app.returnDate').': '. (!is_null($this->history->return_date) ? $this->history->return_date->format('d F Y H:i A') : '-'))
            ->line(__('asset::app.lendBy').': '. $this->history->lender->name)
            ->line(__('asset::app.notes').': '. (!is_null($this->history->notes) ? $this->history->notes : '-'))
            ->line(__('email.thankyouNote'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
