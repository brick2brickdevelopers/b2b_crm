<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Module;
use Modules\Payroll\Entities\PayrollSetting;

class AddPayrollSettingExistingCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       $companies =  \App\Company::all();
        foreach($companies as $company) {
            PayrollSetting::firstOrCreate(['company_id'=>$company->id]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
