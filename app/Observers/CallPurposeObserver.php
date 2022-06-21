<?php

namespace App\Observers;

use App\CallPurpose;

class CallPurposeObserver
{

    public function saving(CallPurpose $callPurpose)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $callPurpose->company_id = company()->id;
        }
    }
}
