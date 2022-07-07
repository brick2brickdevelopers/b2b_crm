<?php

namespace Modules\RestAPI\Listeners;

use App\Events\NewNoticeEvent;
use Illuminate\Support\Str;

class NewNoticePushListener extends BasePushNotification
{

    public function handle(NewNoticeEvent $event)
    {
        $notice =  $event->notice;
        $role = '';
        foreach ($event->notifyUser as $user) {
            $role = $this->getUserRole($user);
            if (isset($event->action) && $event->action == 'update') {
                $this->setMessage($this->updateMessage($notice, 'Notice Update', $role));
            } else {
                $this->setMessage($this->message($notice, 'New Notice', $role));
            }
            $this->sendNotification($user);
        }
    }

    private function message($notice, $title, $role)
    {
        $type = Str::slug($title, '-');
        return [
            'apn' => [
                'notification' => [
                    'title' => __('email.newNotice.subject') . ' #' . $notice->id,
                    'body' => __('email.newNotice.text') . ' - ' . ucwords($notice->heading),
                    'sound' => 'default',
                    'badge' => 1,
                    'id' => $notice->id,
                    'type' => $type,
                    'role' => $role,
                ],
            ],
            'fcm' => [
                'data' => [
                    'title' => __('email.newNotice.subject') . ' #' . $notice->id,
                    'body' => __('email.newNotice.text') . ' - ' . ucwords($notice->heading),
                    'sound' => 'default',
                    'badge' => 1,
                    'id' => $notice->id,
                    'type' => $type,
                    'role' => $role,
                ],
            ]
        ];
    }

    private function updateMessage($notice, $title, $role)
    {
        $type = Str::slug($title, '-');
        return [
            'apn' => [
                'notification' => [
                    'title' => __('email.noticeUpdate.subject') . ' #' . $notice->id,
                    'body' => __('email.noticeUpdate.text') . ' - ' . ucwords($notice->heading),
                    'sound' => 'default',
                    'badge' => 1,
                    'id' => $notice->id,
                    'type' => $type,
                    'role' => $role,
                ],
            ],
            'fcm' => [
                'data' => [
                    'title' => __('email.noticeUpdate.subject') . ' #' . $notice->id,
                    'body' => __('email.noticeUpdate.text') . ' - ' . ucwords($notice->heading),
                    'sound' => 'default',
                    'badge' => 1,
                    'id' => $notice->id,
                    'type' => $type,
                    'role' => $role,
                ],
            ]
        ];
    }
}
