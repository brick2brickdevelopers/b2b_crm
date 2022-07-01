<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Payroll\Entities\SalaryPaymentMethod;

class AddColumnsSalarySlipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_payment_methods', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->unsignedInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');

            $table->string('payment_method');
            $table->boolean('default');
            $table->timestamps();
        });

        Schema::table('salary_slips', function (Blueprint $table) {
            $table->text('salary_json')->nullable();
            $table->text('extra_json')->nullable();
            $table->string('expense_claims')->default('0');
            $table->integer('pay_days');

            $table->unsignedBigInteger('salary_payment_method_id')->nullable();
            $table->foreign('salary_payment_method_id')->references('id')->on('salary_payment_methods')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('salary_slips', function (Blueprint $table) {
            $table->dropColumn(['salary_json']);
            $table->dropColumn(['extra_json']);
            $table->dropColumn(['expense_claims']);
            $table->dropColumn(['pay_days']);

            $table->dropForeign(['salary_payment_method_id']);
            $table->dropColumn(['salary_payment_method_id']);
        });

        Schema::dropIfExists('salary_payment_methods');
    }
}
