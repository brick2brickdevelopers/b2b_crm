<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManualLoggedCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manual_logged_calls', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->integer('lead_id');
            $table->string('lead_number')->nullable();
            $table->string('agent_number')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('outcome')->nullable()->comment("1 => 'in Process';
2 => 'Running';
3 => 'Both Answered';
4 => 'To (Customer) Answered - From (Agent) Unanswered';
5 => 'To (Customer) Answered';
6 => 'To (Customer) Unanswered - From (Agent) Answered.';
7 => 'From (Agent) Unanswered';
8 => 'To (Customer) Unanswered.';
9 => 'Both Unanswered';
10 => 'From (Agent) Answered.';
11 => 'Rejected Call';
12 => 'Skipped';
13 => 'From (Agent) Failed.';
14 => 'To (Customer) Failed - From (Agent) Answered';
15 => 'To (Customer) Failed';
16 => 'To (Customer) Answered - From (Agent) Failed';");
            $table->tinyInteger('call_status')->nullable()->comment('1- available,2- completed,3- follow');
            $table->string('call_initiation')->nullable();
            $table->date('date')->nullable();
            $table->time('duration')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->tinyInteger('call_type')->nullable()->comment('0=Manual,1=Auto');
            $table->tinyInteger('call_source')->nullable()->comment('1=Incoming,0=Outgoing');
            $table->string('call_purpose')->nullable();
            $table->string('call_outcome')->nullable();
            $table->tinyInteger('campaign_id')->nullable();
            $table->string('recordings_file')->nullable();
            $table->string('did')->nullable();
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
        Schema::dropIfExists('manual_logged_calls');
    }
}
