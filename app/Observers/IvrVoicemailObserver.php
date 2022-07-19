<?php

namespace App\Observers;

use App\IvrVoicemail;
use App\VoiceMail;

class IvrVoicemailObserver
{

    public function saving(VoiceMail $voicemails)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $voicemails->company_id = company()->id;
        }
    }
}
