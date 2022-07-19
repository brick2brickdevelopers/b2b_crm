<?php

namespace App;

use App\Observers\IvrVoicemailObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;

class VoiceMail extends Model
{
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CompanyScope);
        static::observe(IvrVoicemailObserver::class);
    }
}
