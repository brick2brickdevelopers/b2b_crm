<?php

namespace Modules\Asset\Observers;

use Modules\Asset\Entities\AssetSetting;

class AssetSettingObserver
{

    public function saving(AssetSetting $assetSetting)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $assetSetting->company_id = company()->id;
        }
    }

}
