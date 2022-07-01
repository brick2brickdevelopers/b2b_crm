<?php

namespace Modules\RestAPI\Entities;

class TicketReply extends \App\TicketReply
{
    // region Properties

    protected $table = 'ticket_replies';

    protected $hidden = [
        'updated_at'
    ];

    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
    ];

    protected $default = [
        'id',
        'message',
        'created_at'
    ];

    protected $filterable = [
        'id',
        'message',
        'ticket_id',
        'user_id',
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

    public function files()
    {
        return $this->hasMany(TicketFile::class, 'ticket_reply_id');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
