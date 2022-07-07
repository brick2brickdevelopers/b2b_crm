<?php

namespace Modules\RestAPI\Http\Controllers;

use App\Events\TaskReminderEvent;
use App\Helper\Reply;
use App\Notifications\TaskReminder;
use App\Role;
use App\TaskUser;
use App\User;
use Froiden\RestAPI\ApiController;
use Froiden\RestAPI\ApiResponse;
use Illuminate\Support\Facades\Notification;
use Modules\RestAPI\Entities\Employee;
use Modules\RestAPI\Entities\Task;
use Modules\RestAPI\Http\Requests\Task\IndexRequest;
use Modules\RestAPI\Http\Requests\Task\CreateRequest;
use Modules\RestAPI\Http\Requests\Task\ShowRequest;
use Modules\RestAPI\Http\Requests\Task\UpdateRequest;
use Modules\RestAPI\Http\Requests\Task\DeleteRequest;

class TaskController extends ApiBaseController
{

    protected $model = Task::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = CreateRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $showRequest = ShowRequest::class;
    protected $deleteRequest = DeleteRequest::class;

    public function modifyIndex($query)
    {
        $query->visibility();
        if (request()->filters && str_contains(request()->filters, 'project_client_id')) {
            $query->rightJoin(
                \DB::raw(
                    '(SELECT `id` as `a_project_id`, 
                `client_id` as `project_client_id`, 
                `deleted_at` as `project_deleted_at`, 
                `project_name` FROM `projects`) as `a`'
                ),
                'a.a_project_id',
                '=',
                'tasks.project_id'
            );
        }
        if (request()->filters && str_contains(request()->filters, 'task_user_id')) {
            $query->join(
                \DB::raw(
                    '(SELECT `task_id` as `tu_task_id`, 
                    `user_id` as `task_user_id` FROM `task_users`) as `tu`'
                ),
                'tu.tu_task_id',
                '=',
                'tasks.id'
            )->groupBy('tasks.id');
        }
        return $query;
    }

    public function stored(Task $task)
    {
        return $this->syncTaskUsers($task);
    }

    public function updated(Task $task)
    {
        return $this->syncTaskUsers($task);
    }

    private function syncTaskUsers(Task $task)
    {
        // To add custom fields data
        if (request()->get('task_users')) {
            $ids = array_column(request()->get('task_users'), 'id');
            $task->users()->sync($ids);
        }

        return $task;
    }

    public function remind($taskID)
    {
        $task = \App\Task::findOrFail($taskID);
        event(new TaskReminderEvent($task));
        return ApiResponse::make(__('messages.reminderMailSuccess'));
    }

    public function me()
    {
        app()->make($this->indexRequest);

        $query = $this->parseRequest()
            ->addIncludes()
            ->addFilters()
            ->addOrdering()
            ->addPaging()
            ->getQuery();


        $query = (new $this->model)->meTaskQuery($query);
        // Load employees relation, if not loaded
        $relations = $query->getEagerLoads();
        $relationRequested = true;

        if (!array_key_exists('users', $relations)) {
            $relationRequested = false;
            $relations['users'] = function ($query) {
                return $query;
            };
        }

        $query->setEagerLoads($relations);

        /** @var Collection $results */
        $results = $this->getResults();


        $results = $results->toArray();

        $meta = $this->getMetaData();

        return ApiResponse::make(null, $results, $meta);
    }
}
