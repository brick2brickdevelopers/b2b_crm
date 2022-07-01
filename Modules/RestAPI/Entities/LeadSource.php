<?php

namespace Modules\RestAPI\Entities;

class LeadSource extends \App\LeadSource
{
    protected $fillable = ['type'];

    protected $default = ['id', 'type'];

    protected $filterable = ['type'];
}
