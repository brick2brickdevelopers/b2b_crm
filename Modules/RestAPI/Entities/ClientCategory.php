<?php

namespace Modules\RestAPI\Entities;

class ClientCategory extends \App\ClientCategory
{
    protected $fillable = ['category_name'];

    protected $default = ['id', 'category_name'];

    protected $guarded = ['id'];

    protected $filterable = ['category_name'];
}
