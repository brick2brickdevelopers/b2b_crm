<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisposalFieldDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disposal_field_data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('disposal_field_id')->unsigned();
            $table->foreign('disposal_field_id')->references('id')->on('disposal_fields')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('model_id')->unsigned();
            $table->string('model')->nullable();
            $table->index('model');
            $table->string('value', 10000);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('disposal_field_data');
    }
}
