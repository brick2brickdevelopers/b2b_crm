<?php

namespace Modules\RestAPI\Entities;

use App\Setting;
use Carbon\Carbon;

class ProjectTimeLog extends \App\ProjectTimeLog
{

    public function __construct($attributes = [])
    {
        $this->appends = array_merge(['timer_start_time'], $this->appends);
        parent::__construct($attributes);
    }
    // region Properties

    protected $table = 'project_time_logs';
    protected $dates = ['start_time', 'end_time'];

    protected $hidden = [
        'updated_at'
    ];

    protected $default = [
        'id',
        'start_time',
        'end_time',
        'memo',
        'task_id',
    ];

    protected $guarded = [
        'id',
    ];

    protected $filterable = [
        'id',
        'project_id',
        'task_id',
        'start_time',
        'end_time',
        'user_id'
    ];

    public function getTimerStartTimeAttribute()
    {
        $settings = Setting::organisationSetting();
//        dd(Carbon::parse($this->start_time)->timezone($settings->timezone));
        return Carbon::parse($this->start_time)->timezone($settings->timezone);
    }

    public function visibleTo(\App\User $user)
    {
        if ($user->hasRole('employee') || $user->cans('view_tasks')) {
            return true;
        }
    }

    public function scopeVisibility($query)
    {
        return $query;
    }
}
