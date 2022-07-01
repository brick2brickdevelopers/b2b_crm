<?php

namespace Modules\Payroll\Observers;

use Modules\Payroll\Entities\SalaryTds;

class SalaryTdsObserver
{

    public function saving(SalaryTds $salaryTds)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $salaryTds->company_id = company()->id;
        }
    }

}
