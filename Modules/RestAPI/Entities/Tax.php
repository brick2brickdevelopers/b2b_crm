<?php

namespace Modules\RestAPI\Entities;

class Tax extends \App\Tax
{
    // region Properties

    protected $table = 'taxes';

    protected $default = [
        'id',
        'tax_name',
        'rate_percent'
    ];

    protected $guarded = [
        'id',
    ];

    protected $filterable = [
        'id',
        'tax_name'
    ];

    public function visibleTo(\App\User $user)
    {
        if ($user) {
            return true;
        }
        return false;
    }

    public function scopeVisibility($query)
    {
        return $query;
    }
}
