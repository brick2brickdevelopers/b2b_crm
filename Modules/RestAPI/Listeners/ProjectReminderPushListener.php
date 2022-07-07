<?php

namespace Modules\RestAPI\Listeners;

use App\Events\NewProjectMemberEvent;
use App\Events\ProjectReminderEvent;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ProjectReminderPushListener extends BasePushNotification
{

    public function handle(ProjectReminderEvent $event)
    {
        $projects =  $event->projects;
        foreach ($projects as $project) {
            $role = $this->getUserRole($event->user);
            $this->setMessage($this->message($project, 'Project Reminder', $event->data, $role));
            $this->sendNotification($event->user);
        }
    }

    private function message($project, $title, $data, $role)
    {
        $type = Str::slug($title, '-');
        return [
            'apn' => [
                'notification' => [
                    'title' => __('email.projectReminder.subject') . ' #' . $project->id,
                    'body' =>
                        __('email.projectReminder.text') . ' ' . Carbon::now($data['global_setting']->timezone)
                            ->addDays($data['project_setting']->remind_time)
                            ->toFormattedDateString(),
                    'sound' => 'default',
                    'badge' => 1,
                    'id' => $project->id,
                    'type' => $type,
                    'role' => $role,
                ],
            ],
            'fcm' => [
                'data' => [
                    'title' => __('email.projectReminder.subject') . ' #' . $project->id,
                    'body' =>
                        __('email.projectReminder.text') . ' ' . Carbon::now($data['global_setting']->timezone)
                            ->addDays($data['project_setting']->remind_time)
                            ->toFormattedDateString(),
                    'sound' => 'default',
                    'badge' => 1,
                    'id' => $project->id,
                    'type' => $type,
                    'role' => $role,
                ],
            ]
        ];
    }
}
