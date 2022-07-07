<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateAssetsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');

            $table->string('name', 100);

            $table->unsignedBigInteger('asset_type_id');
            $table->foreign('asset_type_id')->references('id')->on('asset_types')->onUpdate('cascade')->onDelete('cascade');

            $table->string('serial_number', 255)->nullable();
            $table->index('serial_number');

            $table->text('description')->nullable();
            $table->enum('status', ['lent', 'available','non-functional'])->default('available');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assets');
    }

}
