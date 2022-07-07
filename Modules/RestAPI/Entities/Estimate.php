<?php

namespace Modules\RestAPI\Entities;

class Estimate extends \App\Estimate
{
    // region Properties

    protected $table = 'estimates';

    protected $hidden = [
        'updated_at'
    ];

    protected $default = [
        'id',
        'estimate_number',
        'total',
        'status',
        'valid_till'
    ];

    protected $guarded = [
        'id',
    ];

    protected $filterable = [
        'id',
        'status'
    ];

    public function visibleTo(\App\User $user)
    {
        if ($user->hasRole('admin') || ($user->hasRole('employee') || $user->cans('view_estimates'))) {
            return true;
        }
        return $this->client_id == $user->id;
    }

    public function scopeVisibility($query)
    {
        if (api_user()) {
            $user = api_user();

            if ($user->hasRole('client')) {
                $query->where('estimates.client_id', $user->id);
            }

            return $query;
        }
    }
}
