<?php

namespace Modules\RestAPI\Entities;

use App\User;

class Event extends \App\Event
{
    // region Properties

    protected $table = 'events';
    protected $dates = ['date'];

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'event_attendees');
    }

    protected $default = [
        'id',
    ];

    protected $hidden = [
        'company_id',
    ];

    protected $guarded = [
        'id',
        'company_id',
    ];

    protected $filterable = [
        'id',
    ];
}
