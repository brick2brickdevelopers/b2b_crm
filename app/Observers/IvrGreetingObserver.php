<?php

namespace App\Observers;

use App\IvrGreeting;

class IvrGreetingObserver
{

    public function saving(IvrGreeting $greetings)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $greetings->company_id = company()->id;
        }
    }
}
