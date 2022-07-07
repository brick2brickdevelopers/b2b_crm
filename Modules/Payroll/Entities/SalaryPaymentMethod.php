<?php

namespace Modules\Payroll\Entities;

use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Payroll\Observers\SalaryPaymentMethodObserver;

class SalaryPaymentMethod extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::observe(SalaryPaymentMethodObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    protected $guarded = ['id'];
}
