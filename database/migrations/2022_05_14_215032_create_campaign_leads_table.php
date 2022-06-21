<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_leads', function (Blueprint $table) {
            $table->id();
            $table->integer('campaign_id');
            $table->integer('agent_id');
            $table->integer('lead_id');
            $table->tinyInteger('status');
            $table->tinyInteger('leadcallstatus');
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
        Schema::dropIfExists('campaign_leads');
    }
}
