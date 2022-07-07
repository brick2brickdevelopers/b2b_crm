<?php

namespace Modules\RestAPI\Entities;

class SubTask extends \App\SubTask
{
    // region Properties

    protected $table = 'sub_tasks';

    protected $default = [
        'id',
        'title',
        'due_date',
        'status'
    ];

    protected $hidden = [
    ];

    protected $guarded = [
        'id',
        'task_id',
    ];

    protected $filterable = [
        'id',
        'title',
        'status'
    ];
}
