<?php

namespace App\Observers;

use App\Campaign;

class CampaignObserver
{

    public function saving(Campaign $campaign)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $campaign->company_id = company()->id;
        }
    }
}
