<?php

namespace Modules\RestAPI\Entities;

class EstimateItem extends \App\EstimateItem
{
    // region Properties

    protected $table = 'estimate_items';

    protected $fillable = [
        'id',
        'item_name',
        'type',
        'quantity',
        'unit_price',
        'amount'
    ];
    protected $default = [
        'id',
        'item_name',
        'type',
        'quantity',
        'unit_price',
        'amount'
    ];

    protected $guarded = [
        'id',
    ];

    protected $filterable = [
        'id',
    ];
}
