<?php

namespace App\Observers;

use App\CampaignLead;

class CampaignLeadsObserver
{

    public function saving(CampaignLead $leads)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $leads->company_id = company()->id;
        }
    }
}
