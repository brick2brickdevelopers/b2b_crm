<?php

namespace Modules\RestAPI\Entities;

class LeadCategory extends \App\LeadCategory
{
    protected $fillable = ['category_name'];

    protected $default = ['id', 'category_name'];

    protected $guarded = ['id'];

    protected $filterable = ['category_name'];
}
