<?php

namespace Modules\Payroll\Observers;

use Modules\Payroll\Entities\EmployeeMonthlySalary;

class EmployeeMonthlySalaryObserver
{

    public function saving(EmployeeMonthlySalary $employeeMonthlySalary)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $employeeMonthlySalary->company_id = company()->id;
        }
    }

}
