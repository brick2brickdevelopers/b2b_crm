<?php

namespace App;

use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;

class SipGateway extends Model
{
    protected static function boot()
    {
        parent::boot();
        // static::addGlobalScope(new CompanyScope);
    }
    public function company()
    {
        return  $this->belongsTo(Company::class);
    }
}
