<?php

namespace Modules\RestAPI\Entities;

class Expense extends \App\Expense
{
    // region Properties

    protected $table = 'expenses';

    protected $default = [
        'id',
        'item_name',
        'purchase_date',
        'price',
        'status',
    ];

    protected $hidden = [
        'project_id',
        'user_id',
        'currency_id',
    ];

    protected $dates = [
        'purchase_date',
    ];

    protected $guarded = [
        'id',
    ];

    protected $filterable = [
        'id',
        'item_name',
        'status',
        'project_id',
        'user_id',
        'employee_name',
    ];

    public function visibleTo(\App\User $user)
    {
        if ($user->hasRole('admin') || $user->hasRole('employee') || $user->cans('view_expenses')) {
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

    public function project()
    {
        return $this->belongsTo(\App\Project::class, 'project_id');
    }
}
