<?php

namespace Modules\RestAPI\Entities;

use App\TaskboardColumn;

class Task extends \App\Task
{
    // region Properties

    protected $table = 'tasks';
    protected $fillable = [
        'heading',
        'description',
        'start_date',
        'priority',
        'due_date',
        'is_private',
        'status',
        'board_column_id'
    ];
    protected $appends = ['all_board_columns'];

    protected $default = [
        'id',
        'heading',
        'start_date',
        'priority',
        'due_date',
        'is_private',
        'status',
    ];

    protected $hidden = [
        'user_id',
        'project_id',
        'task_category_id',
        'created_by'
    ];

    protected $guarded = [
        'id',
        'project_id'
    ];

    protected $filterable = [
        'id',
        'tasks.id',
        'heading',
        'project_id',
        'board_column_id',
        'task_category_id',
        'task_user_id',
        'project_client_id',
        'created_by'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'task_users');
    }

    public function project()
    {
        return $this->belongsTo(Project::class)->withTrashed();
    }

    public function getAllBoardColumnsAttribute()
    {
        return TaskboardColumn::all();
    }

    public function meTaskQuery($query)
    {
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
            )->whereNull('project_deleted_at');
        }
        $query->join(
            \DB::raw(
                '(SELECT `task_id` as `tu_task_id`, 
                    `user_id` as `task_user_id` FROM `task_users`) as `tu`'
            ),
            'tu.tu_task_id',
            '=',
            'tasks.id'
        )
            ->join('users as member', 'task_user_id', '=', 'member.id')
            ->leftJoin('users as creator_user', 'creator_user.id', '=', 'tasks.created_by')
            ->join('taskboard_columns', 'taskboard_columns.id', '=', 'tasks.board_column_id');

        $query->where(
            function ($q) {
                $q->where(
                    function ($q1) {
                        $q1->where(
                            function ($q3) {
                                $q3->where('tasks.is_private', 0);
                                $q3->where('task_user_id', api_user()->id);
                            }
                        );
                        $q1->orWhere('tasks.created_by', api_user()->id);
                    }
                );
                $q->orWhere(
                    function ($q2) {
                        $q2->where('tasks.is_private', 1);
                        $q2->where('task_user_id', api_user()->id);
                    }
                );
            }
        );
        return $query;
    }

    public function visibleTo(\App\User $user)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        if (in_array($user->id, [$this->created_by]) || $this->is_private === 0) {
            return true;
        }


        $task = $this;
        $task = $this->meTaskQuery($task);
        $task = $task->get();

        if (!$task->isEmpty()) {
            return true;
        }
        return false;
    }

    public function scopeVisibility($query)
    {
        if (!api_user()) {
            return $query;
        }
        $user = api_user();

        if ($user->hasRole('admin')) {
            return $query;
        }
        // If employee or client show projects assigned
        $query = $this->meTaskQuery($query);
        return $query;
    }
}
