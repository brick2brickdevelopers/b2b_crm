<?php

namespace Modules\RestAPI\Entities;

class Product extends \App\Product
{
    // region Properties

    protected $table = 'products';

    protected $default = [
        'id',
        'name',
        'description',
        'price',
        'taxes'
    ];

    protected $filterable = [
        'id',
        'name',
        'description',
        'price',
        'taxes'
    ];
}
