<?php

namespace Modules\Payroll\Observers;

use Modules\Payroll\Entities\EmployeeSalaryGroup;

class EmployeeSalaryGroupObserver
{

    public function saving(EmployeeSalaryGroup $employeeSalaryGroup)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $employeeSalaryGroup->company_id = company()->id;
        }
    }

}
