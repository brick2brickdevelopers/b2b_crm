<?php

namespace Modules\RestAPI\Listeners;

use App\Events\LeaveEvent;
use App\User;
use Illuminate\Support\Str;

class LeavePushListener extends BasePushNotification
{

    public function handle(LeaveEvent $event)
    {
        $leave =  $event->leave;
        $role = '';
        if ($event->status == 'updated') {
            $role = $this->getUserRole($event->leave->user);
            $this->setMessage($this->leaveUpdatedMessage($leave, 'Leave Updated', $role));
            $this->sendNotification($event->leave->user);
        } elseif ($event->status == 'statusUpdated') {
            if ($event->leave->status == 'approved') {
                $role = $this->getUserRole($event->leave->user);
                $this->setMessage($this->leaveApprovedMessage($leave, 'Leave Approved', $role));
                $this->sendNotification($event->leave->user);
            } else {
                $role = $this->getUserRole($event->leave->user);
                $this->setMessage($this->leaveRejectMessage($leave, 'Leave Reject', $role));
                $this->sendNotification($event->leave->user);
            }
        } elseif ($event->status == 'created') {
            if (!is_null($event->multiDates)) {
                $role = $this->getUserRole($event->leave->user);
                $this->setMessage($this->leaveMultipleMessage('Leave Multiple', $role));
                $this->sendNotification($event->leave->user);
                foreach (User::allAdmins() as $user) {
                    $role = $this->getUserRole($user);
                    $this->setMessage($this->leaveMultipleMessage('Leave Multiple', $role));
                    $this->sendNotification($user);
                }
            } else {
                $role = $this->getUserRole($event->leave->user);
                $this->setMessage($this->leaveMessage($leave, 'Leave Single', $role));
                $this->sendNotification($event->leave->user);
                foreach (User::allAdmins() as $user) {
                    $role = $this->getUserRole($user);
                    $this->setMessage($this->leaveMessage($leave, 'Leave Single', $role));
                    $this->sendNotification($user);
                }
            }
        }
    }

    private function leaveUpdatedMessage($leave, $title, $role)
    {
        $type = Str::slug($title, '-');
        return [
            'apn' => [
                'notification' => [
                    'title' => __('email.leaves.statusSubject'),
                    'body' =>
                        __('app.date') . ': ' . $leave->leave_date->format('d M, Y') .' '.
                        __('app.price') . ': ' . __('app.status') . ': ' . ucwords($leave->status),
                    'sound' => 'default',
                    'badge' => 1,
                    'id' => $leave->id,
                    'type' => $type,
                    'role' => $role,
                ],
            ],
            'fcm' => [
                'data' => [
                    'title' => __('email.leaves.statusSubject'),
                    'body' =>
                        __('app.date') . ': ' . $leave->leave_date->format('d M, Y') .' '.
                        __('app.price') . ': ' . __('app.status') . ': ' . ucwords($leave->status),
                    'sound' => 'default',
                    'badge' => 1,
                    'id' => $leave->id,
                    'type' => $type,
                    'role' => $role,
                ],
            ]
        ];
    }

    private function leaveApprovedMessage($leave, $title, $role)
    {
        $type = Str::slug($title, '-');
        return [
            'apn' => [
                'notification' => [
                    'title' => __('email.leaves.statusSubject'),
                    'body' =>
                        __('email.leave.approve') . ':- ' .
                        __('app.date') . ': ' . $leave->leave_date->format('d M, Y')  .' '.
                        __('app.status') . ': ' . ucwords($leave->status),
                    'sound' => 'default',
                    'badge' => 1,
                    'id' => $leave->id,
                    'type' => $type,
                    'role' => $role,
                ],
            ],
            'fcm' => [
                'data' => [
                    'title' => __('email.leaves.statusSubject'),
                    'body' =>
                        __('email.leave.approve') . ':- ' .
                        __('app.date') . ': ' . $leave->leave_date->format('d M, Y')  .' '.
                        __('app.status') . ': ' . ucwords($leave->status),
                    'sound' => 'default',
                    'badge' => 1,
                    'id' => $leave->id,
                    'type' => $type,
                    'role' => $role,
                ],
            ]
        ];
    }

    private function leaveRejectMessage($leave, $title, $role)
    {
        $type = Str::slug($title, '-');
        return [
            'apn' => [
                'notification' => [
                    'title' => __('email.leaves.statusSubject'),
                    'body' =>
                        __('email.leave.reject') . ':- ' .
                        __('app.date') . ': ' . $leave->leave_date->format('d M, Y')  .' '.
                        __('app.status') . ': ' . ucwords($leave->status),
                    'sound' => 'default',
                    'badge' => 1,
                    'id' => $leave->id,
                    'type' => $type,
                    'role' => $role,
                ],
            ],
            'fcm' => [
                'data' => [
                    'title' => __('email.leaves.statusSubject'),
                    'body' =>
                        __('email.leave.reject') . ':- ' .
                        __('app.date') . ': ' . $leave->leave_date->format('d M, Y')  .' '.
                        __('app.status') . ': ' . ucwords($leave->status),
                    'sound' => 'default',
                    'badge' => 1,
                    'id' => $leave->id,
                    'type' => $type,
                    'role' => $role,
                ],
            ]
        ];
    }

    private function leaveMessage($leave, $title, $role)
    {
        $type = Str::slug($title, '-');
        return [
            'apn' => [
                'notification' => [
                    'title' => __('email.leave.applied'),
                    'body' =>
                        __('email.leave.applied') . ':- ' .
                        __('app.date') . ': ' . $leave->leave_date->toDayDateTimeString() .' '.
                        __('app.status') . ': ' . ucwords($leave->status),
                    'sound' => 'default',
                    'badge' => 1,
                    'id' => $leave->id,
                    'type' => $type,
                    'role' => $role,
                ],
            ],
            'fcm' => [
                'data' => [
                    'title' => __('email.leave.applied'),
                    'body' =>
                        __('email.leave.applied') . ':- ' .
                        __('app.date') . ': ' . $leave->leave_date->toDayDateTimeString() .' '.
                        __('app.status') . ': ' . ucwords($leave->status),
                    'sound' => 'default',
                    'badge' => 1,
                    'id' => $leave->id,
                    'type' => $type,
                    'role' => $role,
                ],
            ]
        ];
    }

    private function leaveMultipleMessage($title, $role)
    {
        $type = Str::slug($title, '-');
        return [
            'apn' => [
                'notification' => [
                    'title' => __('email.leave.applied'),
                    'body' => __('email.leave.applied'),
                    'sound' => 'default',
                    'badge' => 1,
                    'type' => $type,
                    'role' => $role,
                ],
            ],
            'fcm' => [
                'data' => [
                    'title' => __('email.leave.applied'),
                    'body' => __('email.leave.applied'),
                    'sound' => 'default',
                    'badge' => 1,
                    'type' => $type,
                    'role' => $role,
                ],
            ]
        ];
    }
}
