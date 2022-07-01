<?php

namespace Modules\Payroll\Entities;

use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Payroll\Observers\SalaryTdsObserver;

class PayrollCycle extends Model
{
    protected $guarded = ['id'];

    public $table = 'payroll_cycles';

}
