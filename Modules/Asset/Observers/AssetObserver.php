<?php

namespace Modules\Asset\Observers;

use Modules\Asset\Entities\Asset;
use Froiden\RestAPI\Exceptions\ApiException;

class AssetObserver
{

    public function saving(Asset $asset)
    {
        if (company()) {
            $asset->company_id = company()->id;
        }
    }

    public function creating(Asset $asset)
    {
        //region Field conditions

        if ($asset->status === 'lent') {
            // New asset cannot have lent status
            $asset->status = 'available';
        }

        //endregion
    }

    public function updating(Asset $asset)
    {
        //region Field conditions

        $prevAsset = Asset::find($asset->id);

        if ($prevAsset->status == 'lent' && $asset->status == 'non_functional') {
            // Cannot set status to non_function from lent. First, asset should be returned
            throw new ApiException('Asset should be returned before setting status to non functional', null, 422, 422, 2016);
        }

        //endregion
    }

}
