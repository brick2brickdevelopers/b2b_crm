<?php

namespace Modules\RestAPI\Entities;

class Ticket extends \App\Ticket
{
    // region Properties

    protected $table = 'tickets';

    protected $fillable = [
        'user_id',
        'subject',
        'status',
        'priority',
        'agent_id',
        'channel_id',
        'type_id'
    ];

    protected $default = [
        'id',
        'subject',
        'status',
        'priority',
    ];

    protected $hidden = [
        'agent_id',
        'user_id',
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $guarded = [
        'id',
    ];

    protected $filterable = [
        'id',
        'subject',
        'status',
        'agent_id',
        'user_id',
    ];

    public function reply()
    {
        return $this->hasMany(TicketReply::class, 'ticket_id');
    }

    public function type()
    {
        return $this->belongsTo(TicketType::class, 'type_id');
    }

    public function channel()
    {
        return $this->belongsTo(TicketChannel::class, 'channel_id');
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
            $user = api_user();

            if ($user->hasRole('admin')) {
                return $query;
            }
            if ($user->hasRole('employee')) {
                $query->where('agent_id', $user->id);
                return $query;
            }
            if ($user->hasRole('client')) {
                $query->where('user_id', $user->id);
                return $query;
            }
            return $query;
        }
    }
}
