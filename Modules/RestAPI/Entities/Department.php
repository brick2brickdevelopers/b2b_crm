<?php

namespace Modules\RestAPI\Entities;

use App\Team;

class Department extends Team
{
    protected $table = 'teams';

    protected $default = [
        'id',
        'team_name',
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
