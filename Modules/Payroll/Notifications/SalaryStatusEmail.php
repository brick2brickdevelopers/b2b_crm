<?php

namespace Modules\Payroll\Notifications;

use App\Traits\SmtpSettings;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Payroll\Entities\SalarySlip;

class SalaryStatusEmail extends Notification implements ShouldQueue
{
    use Queueable, SmtpSettings;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $salary;
    public function __construct(SalarySlip $salary)
    {
        $this->salary = $salary;
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
            ->subject(__('payroll::modules.payroll.salarySlip') .' '. __('payroll::modules.payroll.' . $this->salary->status) .' '. Carbon::parse($this->salary->year.'-'.$this->salary->month.'-01')->format('F Y'))
            ->greeting(__('email.hello') . ' ' . ucwords($notifiable->name) . '!')
            ->line(__('payroll::email.salaryStatus.text').' '. __('payroll::modules.payroll.' . $this->salary->status))
            ->line(__('app.month').' - '.Carbon::parse($this->salary->year.'-'.$this->salary->month.'-01')->format('F Y'))
            ->action(__('email.loginDashboard'), url('/'))
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
