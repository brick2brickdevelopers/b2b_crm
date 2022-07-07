<?php

namespace Modules\Payroll\Entities;

use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Modules\Payroll\Observers\EmployeePayrollCycleObserver;


class EmployeePayrollCycle extends Model
{
    protected $guarded = ['id'];

    public $table = 'employee_payroll_cycle';

    protected static function boot()
    {
        parent::boot();

        static::observe(EmployeePayrollCycleObserver::class);

        static::addGlobalScope(new CompanyScope);
    }
}
