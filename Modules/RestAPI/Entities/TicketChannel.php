<?php

namespace Modules\RestAPI\Entities;

class TicketChannel extends \App\TicketChannel
{
    // region Properties

    protected $table = 'ticket_channels';

    protected $fillable = [
        'channel_name',
    ];

    protected $default = [
        'id',
        'channel_name',
    ];

    protected $filterable = [
        'id',
        'channel_name',
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
