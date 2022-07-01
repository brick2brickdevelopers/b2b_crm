<?php

namespace Modules\RestAPI\Entities;

class LeaveType extends \App\LeaveType
{
    // region Properties

    protected $table = 'leave_types';

    protected $default = [
        'id',
        'type_name',
    ];

    protected $guarded = [
        'id',
    ];

    protected $filterable = [
        'id',
        'type_name'
    ];

    public function visibleTo(\App\User $user)
    {
        if ($user->hasRole('admin') || $user->hasRole('employee') || $user->cans('view_leave')) {
            return true;
        }

        return false;
    }

    public function scopeVisibility($query)
    {
        if (api_user()) {
            $user = api_user();

            if ($user->hasRole('admin')) {
                return $query;
            }

            if ($user->hasRole('employee')) {
                $query->where('user_id', $user->id);
                return $query;
            }
        }
    }
}
