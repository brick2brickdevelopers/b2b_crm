<?php

namespace Modules\Payroll\Observers;

use Modules\Payroll\Entities\SalaryComponent;

class SalaryComponentObserver
{

    public function saving(SalaryComponent $salaryComponent)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $salaryComponent->company_id = company()->id;
        }
    }

}
