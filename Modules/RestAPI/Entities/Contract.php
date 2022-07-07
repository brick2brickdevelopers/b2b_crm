<?php

namespace Modules\RestAPI\Entities;

class Contract extends \App\Contract
{
    // region Properties

    protected $table = 'contracts';

    protected $default = [
        'id',
        'subject',
        'amount',
        'start_date'
    ];

    protected $guarded = [
        'id',
    ];

    protected $filterable = [
        'id',
        'subject',
        'amount',
        'start_date'
    ];
}
