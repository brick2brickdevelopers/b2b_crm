<?php

namespace Modules\RestAPI\Listeners;

use App\Events\TaskReminderEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TaskReminderPushListener extends BasePushNotification
{

    public function handle(TaskReminderEvent $event)
    {
        $role = '';
        $task =  $event->task;

        foreach ($task->users as $user) {
            $role = $this->getUserRole($user);
            $this->setMessage($this->message($task, 'Task Reminder', $role));
            $this->sendNotification($user);
        }
    }

    private function message($task, $title, $role)
    {
        $type = Str::slug($title, '-');
        return [
            'apn' => [
                'notification' => [
                    'title' => $title . ' #' . $task->id,
                    'body' => $task->heading . ($task->project ? ' - Project: ' . $task->project->project_name : ''),
                    'sound' => 'default',
                    'badge' => 1,
                    'id' => $task->id,
                    'type' => $type,
                    'role' => $role,
                ],
            ],
            'fcm' => [
                'data' => [
                    'title' => $title . ' #' . $task->id,
                    'body' => $task->heading . ($task->project ? ' - Project: ' . $task->project->project_name : ''),
                    'sound' => 'default',
                    'badge' => 1,
                    'id' => $task->id,
                    'type' => $type,
                    'role' => $role,
                ],
            ]
        ];
    }
}
