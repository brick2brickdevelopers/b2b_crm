<?php

namespace App\Observers;

use App\Callingdata;
use App\CampaignLead;

class CallingDataObserver
{

    public function saving(Callingdata $leads)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $leads->company_id = company()->id;
        }
    }
}
