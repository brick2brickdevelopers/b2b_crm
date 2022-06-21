<?php

namespace App;


use App\Observers\CallPurposeObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CustomFieldsTrait;

class CallPurpose extends Model
{
    use CustomFieldsTrait;
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CompanyScope);
        static::observe(CallPurposeObserver::class);
    }
}
