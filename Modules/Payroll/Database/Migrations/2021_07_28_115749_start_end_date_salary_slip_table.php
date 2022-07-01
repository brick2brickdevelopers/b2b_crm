<?php

use App\Scopes\CompanyScope;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Payroll\Entities\PayrollCycle;
use Modules\Payroll\Entities\EmployeePayrollCycle;
use Modules\Payroll\Entities\SalarySlip;

class StartEndDateSalarySlipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $salarySlip = SalarySlip::whereNull('salary_from')->whereNull('salary_to')->withoutGlobalScopes([CompanyScope::class])->get();
        foreach($salarySlip as $slip){
            $slip->salary_from = Carbon::parse(Carbon::parse('01-' . $slip->month . '-' . $slip->year))->startOfMonth()->toDateString();
            $slip->salary_to = Carbon::parse(Carbon::parse('01-' . $slip->month . '-' . $slip->year))->endOfMonth()->toDateString();
            $slip->save();
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('salary_components', function (Blueprint $table) {
            $table->dropColumn('weekly_value');
            $table->dropColumn('biweekly_value');
            $table->dropColumn('semimonthly_value');
        });
    }
}
