<?php

namespace Modules\Payroll\Observers;

use Modules\Payroll\Entities\SalaryGroup;

class SalaryGroupObserver
{

    public function saving(SalaryGroup $salaryGroup)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $salaryGroup->company_id = company()->id;
        }
    }

}
