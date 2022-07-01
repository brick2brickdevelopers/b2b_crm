<?php

namespace Modules\Payroll\Listeners;

use App\Events\CompanyRegistered;
use Modules\Payroll\Entities\PayrollSetting;

class NewPayrollSettingListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CompanyRegistered $event)
    {
        $company = $event->company;
        $fetchSetting = new PayrollSetting();
        $fetchSetting->company_id = $company->id;
        $fetchSetting->save();

    }
}
