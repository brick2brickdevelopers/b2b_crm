<?php

namespace Modules\Payroll\Entities;

use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Payroll\Observers\SalaryGroupComponentObserver;

class SalaryGroupComponent extends Model
{
    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::observe(SalaryGroupComponentObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function group()
    {
        return $this->belongsTo(SalaryGroup::class);
    }

    public function component()
    {
        return $this->belongsTo(SalaryComponent::class, 'salary_component_id');
    }
}
