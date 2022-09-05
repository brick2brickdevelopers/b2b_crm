<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallFlowDiagramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('call_flow_diagrams', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id');
            $table->integer('greetings_id')->nullable();
            $table->boolean('menu');
            $table->integer('menu_message')->nullable();
            $table->json('extensions')->nullable();
            $table->boolean('voicemail');
            $table->boolean('non_working_hours');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('non_working_hours_greetings')->nullable();
            $table->integer('non_working_hours_voicemail')->nullable();
            $table->boolean('non_working_days');
            $table->json('days')->nullable();
            $table->integer('non_working_days_greetings')->nullable();
            $table->integer('non_working_days_voicemail')->nullable();
            $table->integer('did_number')->nullable();
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
        Schema::dropIfExists('call_flow_diagrams');
    }
}
