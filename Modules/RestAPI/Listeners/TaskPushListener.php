<?php

namespace Modules\RestAPI\Listeners;

use App\Events\TaskEvent;
use Illuminate\Support\Str;

class TaskPushListener extends BasePushNotification
{

    public function handle(TaskEvent $event)
    {
        $task = $event->task;
        $role = '';
        // NewClientTask, NewTask, TaskUpdated, TaskCompleted, TaskUpdatedClient
        if ($event->notificationName !== 'NewClientTask'
            && $event->notificationName !== 'TaskUpdatedClient'
            && $event->notificationName != 'TaskCompletedClient') {
            $title = ucwords(Str::snake($event->notificationName, ' '));

            foreach ($event->notifyUser as $user) {
                $role = $this->getUserRole($user);
                $this->setMessage($this->message($task, $title, $role));
                $this->sendNotification($user);
            }
        }
    }

    private function message($task, $title, $role)
    {
        $type = Str::slug($title, '-');
        return [
            'apn' => [
                'notification' => [
                    'title' => $title . ' #' . $task->id,
                    'body' => $task->heading . ($task->project ? ' - Project:' . $task->project->project_name : ''),
                    'sound' => 'default',
                    'badge' => 1,
                    'id' => $task->id,
                    'type' => 'task',
                    'role' => $role,
                ],
            ],
            'fcm' => [
                'data' => [
                    'title' => $title . ' #' . $task->id,
                    'body' => $task->heading . ($task->project ? ' - Project:' . $task->project->project_name : ''),
                    'sound' => 'default',
                    'badge' => 1,
                    'id' => $task->id,
                    'type' => 'task',
                    'role' => $role,
                ],
            ]
        ];
    }
}
