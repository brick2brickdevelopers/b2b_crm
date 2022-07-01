<?php

namespace Modules\Payroll\Entities;

use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Payroll\Observers\EmployeeMonthlySalaryObserver;

class EmployeeMonthlySalary extends Model
{
    protected $guarded = ['id'];

    protected $dates = ['date'];

    protected static function boot()
    {
        parent::boot();

        static::observe(EmployeeMonthlySalaryObserver::class);

        static::addGlobalScope(new CompanyScope);
    }


    public static function employeeNetSalary($userId, $tillDate = null)
    {
        $initialSalary = EmployeeMonthlySalary::where('user_id', $userId)
            ->where('type', '=', 'initial')
            ->sum('amount');

        $addSalary = EmployeeMonthlySalary::where('user_id', $userId)
            ->where('type', '=', 'increment');
        if (!is_null($tillDate)) {
            $addSalary = $addSalary->where('date', '<=', $tillDate);
        }
        $addSalary = $addSalary->sum('amount');

        $subtractSalary = EmployeeMonthlySalary::where('user_id', $userId)
            ->where('type', '=', 'decrement');
        if (!is_null($tillDate)) {
            $subtractSalary = $subtractSalary->where('date', '<=', $tillDate);
        }
        $subtractSalary = $subtractSalary->sum('amount');

        $netSalary = ($initialSalary + $addSalary - $subtractSalary);
        if ($netSalary < 0) {
            $netSalary = 0;
        }

        return [
            'netSalary' => $netSalary,
            'initialSalary' => $initialSalary
        ];
    }

    public static function employeeIncrements($userId)
    {
        return EmployeeMonthlySalary::where('user_id', $userId)
            ->where('type', '=', 'increment')
            ->get();
    }
}
