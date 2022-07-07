<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Payroll\Entities\PayrollCycle;
use Modules\Payroll\Entities\EmployeePayrollCycle;
use Modules\Payroll\Entities\SalarySlip;

class AlertSalaryComponentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('salary_components', function (Blueprint $table) {
            $table->double('weekly_value')->default(0);
            $table->double('biweekly_value')->default(0);
            $table->double('semimonthly_value')->default(0);
        });
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
