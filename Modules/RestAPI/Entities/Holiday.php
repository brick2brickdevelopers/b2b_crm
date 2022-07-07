<?php

namespace Modules\RestAPI\Entities;

use DateTimeInterface;

class Holiday extends \App\Holiday
{
    // region Properties


    protected $table = 'holidays';
    protected $dates = ['date'];


    protected $default = [
        'id',
        'date',
        'occassion'
    ];

    protected $filterable = [
        'id',
        'date',
        'occassion'
    ];

    //endregion
}
