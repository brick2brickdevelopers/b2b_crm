<?php

namespace Modules\Asset\Observers;

use Modules\Asset\Entities\AssetType;
use Froiden\RestAPI\Exceptions\ApiException;

class AssetTypeObserver
{

    public function saving(AssetType $asset)
    {
        if (company()) {
            $asset->company_id = company()->id;
        }
    }
}
