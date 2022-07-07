<?php

namespace Modules\RestAPI\Entities;

class TaskboardColumn extends \App\TaskboardColumn
{
    // region Properties

    protected $table = 'taskboard_columns';

    protected $default = [
        'id',
        'column_name',
        'slug',
        'label_color',
        'priority',
    ];

    protected $hidden = [
    ];

    protected $guarded = [
        'id',
    ];

    protected $filterable = [
        'id',
        'column_name',
        'slug'
    ];
}
