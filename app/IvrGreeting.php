<?php

namespace App;

use App\Observers\IvrGreetingObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;

class IvrGreeting extends Model
{
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CompanyScope);
        static::observe(IvrGreetingObserver::class);
    }
}
