<?php

namespace Modules\RestAPI\Entities;

class TicketGroup extends \App\TicketGroup
{
    // region Properties

    protected $table = 'ticket_groups';

    protected $fillable = [
        'group_name',
    ];

    protected $default = [
        'id',
        'group_name',
    ];

    protected $filterable = [
        'id',
        'group_name',
    ];

    public function agents()
    {
        return $this->belongsToMany(
            User::class,
            'ticket_agent_groups',
            'group_id',
            'agent_id'
        )
            ->where('ticket_agent_groups.status', '=', 'enabled');
    }

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
