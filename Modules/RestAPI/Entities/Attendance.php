<?php

namespace Modules\RestAPI\Entities;

class Attendance extends \App\Attendance
{
    // region Properties

    protected $table = 'attendances';

    protected $default = [
        'id',
        'clock_in_date',
        'clock_in_time'
    ];

    protected $hidden = [
        'user_id',
    ];

    protected $guarded = [
        'id',
    ];

    protected $filterable = [
        'id',
        'clock_in_time',
        'user_id',
    ];

    public function visibleTo(\App\User $user)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        if (($user->hasRole('employee') && $user->cans('view_attendance')) || $this->user_id == $user->id) {
            return true;
        }
        return false;
    }

    public function scopeVisibility($query)
    {
        if (api_user()) {
            $user = api_user();

            if ($user->hasRole('admin') || ($user->hasRole('employee') && $user->cans('view_attendance'))) {
                return $query;
            }

            if (!$user->cans('view_attendance')) {
                $query->where('user_id', $user->id);
                return $query;
            }
        }
    }
}
