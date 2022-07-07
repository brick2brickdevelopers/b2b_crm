<?php

namespace Modules\Payroll\Observers;

use Modules\Payroll\Entities\EmployeePayrollCycle;

class EmployeePayrollCycleObserver
{

    public function saving(EmployeePayrollCycle $employeePayrollCycle)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $employeePayrollCycle->company_id = company()->id;
        }
    }

}
