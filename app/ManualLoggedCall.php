<?php

namespace App;

use App\Observers\ManualLoggedCallObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CustomFieldsTrait;

class ManualLoggedCall extends Model
{
    use CustomFieldsTrait;
    protected static function boot()
    {

        parent::boot();
        static::addGlobalScope(new CompanyScope);
        static::observe(ManualLoggedCallObserver::class);
    }


    public function purpose()
    {
        return $this->belongsTo(CallPurpose::class, 'call_purpose');
    }

    protected $guarded = [];  

}
