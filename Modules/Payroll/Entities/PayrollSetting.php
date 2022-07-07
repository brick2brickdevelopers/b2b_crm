<?php

namespace Modules\Payroll\Entities;

use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Payroll\Observers\PayrollSettingObserver;

class PayrollSetting extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::observe(PayrollSettingObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    protected $guarded = ['id'];
}
