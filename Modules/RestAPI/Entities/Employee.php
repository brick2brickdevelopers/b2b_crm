<?php

namespace Modules\RestAPI\Entities;

use App\TaskboardColumn;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Employee extends \App\User
{
    // region Properties

//    protected $appends = ['pending_task', 'leaves_taken', 'projects_count'];

    public function __construct($attributes = [])
    {
        $this->appends = array_merge(['pending_task', 'leaves_taken', 'projects_count'], $this->appends);
        parent::__construct($attributes);
    }


    protected $table = 'users';


    protected $default = [
        'id',
        'name',
        'email',
    ];

    protected $hidden = [
        'employee_detail.department_id',
        'employee_detail.designation_id'
    ];

    protected $guarded = [
        'id',
    ];

    protected $filterable = [
        'id',
        'users.name',
        'status'
    ];

    public function getPendingTaskAttribute()
    {
        $completedTaskColumn = TaskboardColumn::completeColumn();
        return Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->where('tasks.board_column_id', '<>', $completedTaskColumn->id)
            ->where(DB::raw('DATE(due_date)'), '<=', Carbon::today()->format('Y-m-d'))
            ->where('task_users.user_id', $this->id)
            ->select('tasks.*')
            ->groupBy('tasks.id')
            ->get()
            ->count();
    }

    public function getProjectsCountAttribute()
    {
        return Project::join('project_members', 'project_members.project_id', '=', 'projects.id')
            ->where('project_members.user_id', $this->id)
            ->select('projects.*')
            ->groupBy('projects.id')
            ->get()
            ->count();
    }

    public function getLeavesTakenAttribute()
    {
        return Leave::where('user_id', $this->id)
            ->where('status', 'approved')
            ->get()
            ->count();
    }

    public function visibleTo(\App\User $user)
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return false;
    }

    public function scopeVisibility($query)
    {
        if (api_user()) {
            $user = api_user();

            $query->withoutGlobalScope('active')->join('role_user', 'role_user.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->select('users.id', 'users.name as name', 'users.email', 'users.created_at')
                ->where('roles.name', '<>', 'client')
                ->orderBy('users.id')
                ->groupBy('users.id');

            if ($user->hasRole('admin')) {
                return $query;
            }
        }
    }

    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = \Hash::make($value);
        }
    }
}
