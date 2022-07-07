<?php

namespace Modules\RestAPI\Entities;

class LeadAgent extends \App\LeadAgent
{
    protected $fillable = ['user_id'];

    protected $default = ['id', 'user_id'];

    protected $guarded = ['id'];

    protected $filterable = ['user_id'];
}
