<?php

namespace Modules\Payroll\Observers;

use Modules\Payroll\Entities\SalaryGroupComponent;

class SalaryGroupComponentObserver
{

    public function saving(SalaryGroupComponent $salaryGroupComponent)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $salaryGroupComponent->company_id = company()->id;
        }
    }

}
