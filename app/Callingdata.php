<?php

namespace App;

use App\Observers\CallingDataObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;

class Callingdata extends Model
{
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CompanyScope);
        static::observe(CallingDataObserver::class);
    }
    public $timestamps = false;
    protected $table = 'callingdata';
}
