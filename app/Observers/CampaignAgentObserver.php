<?php

namespace App\Observers;

use App\Campaign;
use App\CampaignAgent;

class CampaignAgentObserver
{

    public function saving(CampaignAgent $agent)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $agent->company_id = company()->id;
        }
    }
}
