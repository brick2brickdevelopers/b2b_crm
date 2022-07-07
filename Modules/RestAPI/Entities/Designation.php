<?php

namespace Modules\RestAPI\Entities;

class Designation extends \App\Designation
{
    protected $table = 'designations';

    protected $default = [
        'id',
        'name',
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $filterable = [
        'id',
        'team_name',
    ];
}
