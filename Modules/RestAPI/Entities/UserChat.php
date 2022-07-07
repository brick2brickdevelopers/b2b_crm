<?php

namespace Modules\RestAPI\Entities;

class UserChat extends \App\UserChat
{
    public function __construct($attributes = [])
    {
        $this->appends = array_merge(['message_seen_status', 'message_time'], $this->appends);
        parent::__construct($attributes);
    }

    // region Properties

    protected $table = 'users_chat';

    protected $fillable = [
        'from',
        'to',
        'message_seen',
        'user_one',
        'user_id',
        'message',
    ];

    protected $default = [
        'id',
        'from',
        'to',
        'message_seen',
        'user_one',
        'user_id',
        'message',
    ];

    protected $hidden = [
//        'from',
//        'to',
//        'user_one',
//        'user_id',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $guarded = [
        'id',
    ];

    protected $filterable = [
        'id',
        'from',
        'to',
        'message_seen',
        'user_one',
        'user_id',
        'message',
    ];

    public function getMessageSeenStatusAttribute()
    {
        return $this->to === api_user()->id ? $this->message_seen : 'yes';
    }

    public function getMessageTimeAttribute()
    {
        return $this->created_at->diffForHumans();
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
