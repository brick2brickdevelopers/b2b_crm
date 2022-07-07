<?php

namespace Modules\Payroll\Entities;

use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Payroll\Observers\SalaryTdsObserver;

class SalaryTds extends Model
{
    protected $guarded = ['id'];

    public $table = 'salary_tds';

    protected static function boot()
    {
        parent::boot();

        static::observe(SalaryTdsObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

}
