<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class ApplicationSettings extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rest_api_application_settings', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name', 255);
            $table->integer('app_key');
            $table->unique('app_key');
            $table->string('app_secret', 60)->nullable(); // App secret is null first time when new app is created

            $table->unsignedInteger('authorized_employee_id')->nullable();
            $table->foreign('authorized_employee_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->enum('status', ['enabled', 'disabled'])->default('enabled');

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
        Schema::dropIfExists('rest_api_application_settings');
    }
}
