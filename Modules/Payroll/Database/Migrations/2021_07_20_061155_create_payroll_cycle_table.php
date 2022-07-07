<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Payroll\Entities\PayrollCycle;
use Modules\Payroll\Entities\SalarySlip;
use Carbon\Carbon;

class CreatePayrollCycleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_cycles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('cycle')->nullable();
            $table->enum('status',['active','inactive'])->default('active');
            $table->timestamps();
        });

        $payroll = New PayrollCycle();
        $payroll->cycle = 'monthly';
        $payroll->save();

        $payroll = New PayrollCycle();
        $payroll->cycle = 'weekly';
        $payroll->save();

        $payroll = New PayrollCycle();
        $payroll->cycle = 'biweekly';
        $payroll->save();

        $payroll = New PayrollCycle();
        $payroll->cycle = 'semimonthly';
        $payroll->save();

        //salary_slips
        $payrollCycle = PayrollCycle::where('cycle', 'monthly')->first();

        Schema::table('salary_slips', function (Blueprint $table) {
            $table->dateTime('salary_from')->nullable();
            $table->dateTime('salary_to')->nullable();
            $table->unsignedBigInteger('payroll_cycle_id')->nullable();
            $table->foreign('payroll_cycle_id')->references('id')->on('payroll_cycles')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('payroll_settings', function (Blueprint $table) {
            $table->integer('semi_monthly_start')->nullable()->default(1);
            $table->integer('semi_monthly_end')->nullable()->default(30);
        });

        $companies = \App\Company::all();
        foreach($companies as $company){
            $salaries = SalarySlip::where('company_id', $company->id)->get();
            foreach($salaries as $salary){
                $dates = $this->getMonthDates($salary->month,$salary->year);
                if($dates){
                    $salary->salary_from        = $dates['startDate'];
                    $salary->salary_to          = $dates['endDate'];
                    $salary->payroll_cycle_id   = $payrollCycle->id;
                    $salary->save();
                }
            }
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('super_admin_payroll_settings');
    }

    public function getMonthDates($month,$year){
        $monthDate = Carbon::createFromFormat('Y-m-d', $year.'-'.$month.'-1');
        $startDate = $monthDate->firstOfMonth()->format('Y-m-d');
        $endDate = $monthDate->endOfMonth()->format('Y-m-d');
        $dates =  ['startDate' => $startDate, 'endDate' => $endDate];
        return $dates;
    }
}
