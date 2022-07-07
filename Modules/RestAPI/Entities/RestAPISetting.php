<?php

namespace Modules\RestAPI\Entities;

use Illuminate\Database\Eloquent\Model;

class RestAPISetting extends Model
{
    // region Properties

    protected $table = 'rest_api_settings';

    protected $default = [
        'id',
    ];
}
