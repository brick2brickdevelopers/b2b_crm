<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisposalFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disposal_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id');
            $table->integer('disposal_field_group_id')->unsigned()->nullable();
            $table->foreign('disposal_field_group_id')->references('id')->on('disposal_field_groups')
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->string('label', 100);
            $table->string('name', 100);
            $table->string('type', 10);
            $table->enum('required', ['yes', 'no'])->default('no');
            $table->string('values', 5000)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('disposal_fields');
    }
}
