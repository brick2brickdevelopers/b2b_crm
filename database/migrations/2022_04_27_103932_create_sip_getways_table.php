<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSipGetwaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sip_gateways', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id')->unique();
            $table->integer('type');
            $table->string('caller_id')->nullable();
            $table->string('endpoint')->nullable();
            $table->string('key')->nullable();
            $table->string('token')->nullable();
            $table->boolean('status');
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
        Schema::dropIfExists('sip_gateways');
    }
}
