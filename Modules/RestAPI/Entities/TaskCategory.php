<?php

namespace Modules\RestAPI\Entities;

class TaskCategory extends \App\TaskCategory
{
    // region Properties

    protected $table = 'task_category';

    protected $default = [
        'id',
        'title'
    ];

    protected $hidden = [
    ];

    protected $guarded = [
        'id',
    ];

    protected $filterable = [
        'id',
        'title'
    ];
}
