<?php

namespace Modules\RestAPI\Listeners;

use App\Events\NewProjectMemberEvent;
use Illuminate\Support\Str;

class ProjectMemberPushListener extends BasePushNotification
{

    public function handle(NewProjectMemberEvent $event)
    {
        $role = '';
        $role = $this->getUserRole($event->projectMember->user);
        $project =  $event->projectMember->project;
        $this->setMessage($this->message($project, 'Project Member', $role));
        $this->sendNotification($event->projectMember->user);
    }

    private function message($project, $title, $role)
    {
        $type = Str::slug($title, '-');
        return [
            'apn' => [
                'notification' => [
                    'title' => __('email.newProjectMember.subject') . ' #' . $project->id,
                    'body' => __('email.newProjectMember.text') . ' - ' . ucwords($project->project_name),
                    'sound' => 'default',
                    'badge' => 1,
                    'id' => $project->id,
                    'type' => $type,
                    'role' => $role,
                ],
            ],
            'fcm' => [
                'data' => [
                    'title' => __('email.newProjectMember.subject') . ' #' . $project->id,
                    'body' => __('email.newProjectMember.text') . ' - ' . ucwords($project->project_name),
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
