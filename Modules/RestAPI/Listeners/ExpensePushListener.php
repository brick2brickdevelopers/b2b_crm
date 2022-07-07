<?php

namespace Modules\RestAPI\Listeners;

use App\Events\NewExpenseEvent;
use App\User;
use Illuminate\Support\Str;

class ExpensePushListener extends BasePushNotification
{

    public function handle(NewExpenseEvent $event)
    {
        $expense =  $event->expense;
        $role = '';
        if ($event->status == 'admin') {
            $role = $this->getUserRole($event->expense->user);
            $this->setMessage($this->message($expense, 'Expense Member', $role));
            $this->sendNotification($event->expense->user);
        } elseif ($event->status == 'member') {
            foreach (User::allAdmins() as $user) {
                $role = $this->getUserRole($user);
                $this->setMessage($this->message($expense, 'Expense Admin', $role));
                $this->sendNotification($user);
            }
        } else {
            $role = $this->getUserRole($event->expense->user);
            $this->setMessage($this->updateMessage($expense, 'Expense Updated', $role));
            $this->sendNotification($event->expense->user);
        }
    }

    private function message($expense, $title, $role)
    {
        $type = Str::slug($title, '-');
        return [
            'apn' => [
                'notification' => [
                    'title' => __('email.newExpense.subject'),
                    'body' =>
                        $expense->item_name .' '.
                        __('app.price') . ': ' . $expense->currency->currency_symbol . $expense->price,
                    'sound' => 'default',
                    'badge' => 1,
                    'id' => $expense->id,
                    'type' => $type,
                    'role' => $role,
                ],
            ],
            'fcm' => [
                'data' => [
                    'title' => __('email.newExpense.subject'),
                    'body' =>
                        $expense->item_name .' '.
                        __('app.price') . ': ' . $expense->currency->currency_symbol . $expense->price,
                    'sound' => 'default',
                    'badge' => 1,
                    'id' => $expense->id,
                    'type' => $type,
                    'role' => $role,
                ],
            ]
        ];
    }

    private function updateMessage($expense, $title, $role)
    {
        $type = Str::slug($title, '-');
        return [
            'apn' => [
                'notification' => [
                    'title' => __('email.expenseStatus.subject'),
                    'body' =>
                        $expense->item_name . ' - ' . __('email.expenseStatus.text') . ' ' . $expense->status . '.',
                    'sound' => 'default',
                    'badge' => 1,
                    'id' => $expense->id,
                    'type' => $type,
                    'role' => $role,
                ],
            ],
            'fcm' => [
                'data' => [
                    'title' => __('email.expenseStatus.subject'),
                    'body' =>
                        $expense->item_name . ' - ' . __('email.expenseStatus.text') . ' ' . $expense->status . '.',
                    'sound' => 'default',
                    'badge' => 1,
                    'id' => $expense->id,
                    'type' => $type,
                    'role' => $role,
                ],
            ]
        ];
    }
}
