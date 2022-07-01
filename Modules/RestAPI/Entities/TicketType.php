<?php

namespace Modules\RestAPI\Entities;

class TicketType extends \App\TicketType
{
    // region Properties

    protected $table = 'ticket_types';

    protected $fillable = [
        'type',
    ];

    protected $default = [
        'id',
        'type',
    ];

    protected $filterable = [
        'id',
        'type',
    ];

    public function visibleTo(\App\User $user)
    {
        if ($user->hasRole('admin') || $user->hasRole('employee')) {
            return true;
        }

        return false;
    }

    public function scopeVisibility($query)
    {
        if (api_user()) {
            return $query;
        }
    }
}
