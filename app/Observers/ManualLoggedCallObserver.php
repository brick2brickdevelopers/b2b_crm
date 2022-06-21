<?php

namespace App\Observers;

use App\ManualLoggedCall;
use App\MessageSetting;

class ManualLoggedCallObserver
{

    public function saving(ManualLoggedCall $message)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $message->company_id = company()->id;
        }
    }
}
