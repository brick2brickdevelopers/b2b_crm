<?php

namespace Modules\Payroll\Observers;

use Modules\Payroll\Entities\PayrollSetting;

class PayrollSettingObserver
{

    public function saving(PayrollSetting $payrollSetting)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $payrollSetting->company_id = company()->id;
        }
    }

}
