<?php

namespace Modules\Payroll\Observers;

use Modules\Payroll\Entities\SalarySlip;

class SalarySlipObserver
{

    public function saving(SalarySlip $salarySlip)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $salarySlip->company_id = company()->id;
        }
    }

}
