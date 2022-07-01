<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Payroll\Entities\PayrollCycle;
use Modules\Payroll\Entities\EmployeePayrollCycle;
use Modules\Payroll\Entities\SalarySlip;

class CreateEmployeePayrollCycleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_payroll_cycle', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('payroll_cycle_id')->nullable();
            $table->foreign('payroll_cycle_id')->references('id')->on('payroll_cycles')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });

        $payrollCycle = PayrollCycle::where('cycle', 'monthly')->first();

        $companies = \App\Company::all();
        foreach($companies as $company){
            $users = \App\User::where('company_id', $company->id)->get();
            foreach($users as $userData){
                if($userData){
                    $cycle = new EmployeePayrollCycle ();
                    $cycle->company_id = $company->id;
                    $cycle->payroll_cycle_id = $payrollCycle->id;
                    $cycle->user_id = $userData->id;
                    $cycle->save();
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
        Schema::dropIfExists('employee_payroll_cycle');
    }
}
