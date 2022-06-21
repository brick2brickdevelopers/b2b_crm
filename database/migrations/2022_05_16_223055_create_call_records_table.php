<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CallRecords', function (Blueprint $table) {
            $table->id();
            $table->string('source_number');
            $table->string('caller_id_name');
            $table->string('caller_id_number')->nullable();
            $table->string('callerid')->nullable();
            $table->string('destination_number')->nullable();
            $table->string('context');
            $table->timestamp('start_stamp')->nullable()->useCurrentOnUpdate();
            $table->timestamp('answer_stamp')->nullable()->useCurrentOnUpdate();
            $table->timestamp('end_stamp')->nullable()->useCurrentOnUpdate();
            $table->string('duration');
            $table->string('billsec');
            $table->string('actual_duration')->nullable();
            $table->string('hangup_cause');
            $table->string('uuid');
            $table->string('bleg_uuid')->nullable();
            $table->string('accountcode')->nullable();
            $table->string('read_codec')->nullable();
            $table->string('write_codec')->nullable();
            $table->string('calltype')->nullable();
            $table->string('sip_cause_code')->nullable();
            $table->string('sip_cause_reason')->nullable();
            $table->string('q959_cause_code')->nullable();
            $table->string('sip_hangup_disposition')->nullable();
            $table->string('direction')->nullable();
            $table->string('user_id')->nullable();
            $table->string('carrier_ip')->nullable();
            $table->string('carrier_port')->nullable();
            $table->string('remote_media_port')->nullable();
            $table->string('carrier_media_ip')->nullable();
            $table->string('pdd')->nullable();
            $table->timestamp('current_cdr_time')->nullable()->useCurrentOnUpdate();
            $table->integer('leadid')->nullable();
            $table->integer('company_id')->nullable();
            $table->integer('agentid')->nullable();
            $table->integer('ucid')->nullable();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('CallRecords');
    }
}
