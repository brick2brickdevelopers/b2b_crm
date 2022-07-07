<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PayrollGenerateAllowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_monthly_salaries', function (Blueprint $table) {
            $table->enum('allow_generate_payroll', ['yes', 'no'])->default('yes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_monthly_salaries', function (Blueprint $table) {
            $table->text('allow_generate_payroll')->nullable();
        });
    }
}
