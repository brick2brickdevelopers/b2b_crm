<?php

namespace Modules\RestAPI\Entities;

class TicketFile extends \App\TicketFile
{
    // region Properties

    protected $table = 'ticket_files';

    protected $hidden = [
        'updated_at'
    ];

    protected $fillable = [
        'ticket_reply_id',
        'user_id',
        'filename',
        'hashname',
    ];

    protected $default = [
        'id',
    ];

    protected $filterable = [
        'id',
        'user_id',
    ];

    protected $appends = ['file_url','icon'];

    public function getFileUrlAttribute()
    {
        if (!is_null($this->external_link)) {
            return
                $this->external_link;
        } else {
            return
                asset_url_local_s3('ticket-files/' . $this->ticket_reply_id . '/' . $this->hashname);
        }
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
