<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallingGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calling_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id');
            $table->string('calling_group_name');
            $table->string('fallback_number')->nullable();
            $table->json('employees')->nullable();
            $table->boolean('is_default');
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
        Schema::dropIfExists('calling_groups');
    }
}
