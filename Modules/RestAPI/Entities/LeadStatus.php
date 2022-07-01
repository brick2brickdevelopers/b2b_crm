<?php

namespace Modules\RestAPI\Entities;

class LeadStatus extends \App\LeadStatus
{
    protected $fillable = ['type', 'priority', 'default', 'label_color'];

    protected $default = ['id', 'type', 'priority', 'default', 'label_color'];

    protected $guarded = ['id'];

    protected $filterable = ['type', 'priority', 'default', 'label_color'];
}
