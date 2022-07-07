<?php

namespace Modules\RestAPI\Entities;

class ContractType extends \App\ContractType
{
    // region Properties

    protected $table = 'contract_types';

    protected $default = [
        'id',
        'name'
    ];



    protected $guarded = [
        'id',
    ];

    protected $filterable = [
        'id',
        'name'
    ];

    //endregion

    //region Boot
}
