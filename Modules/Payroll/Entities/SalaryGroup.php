<?php

namespace Modules\Payroll\Entities;

use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Payroll\Observers\SalaryGroupObserver;

class SalaryGroup extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::observe(SalaryGroupObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    protected $guarded = ['id'];

    public function components()
    {
        return $this->hasMany(SalaryGroupComponent::class);
    }

    public function employees()
    {
        return $this->hasMany(EmployeeSalaryGroup::class, 'salary_group_id');
    }
}
