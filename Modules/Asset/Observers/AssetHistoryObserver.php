<?php

namespace Modules\Asset\Observers;

use Froiden\RestAPI\Exceptions\ApiException;
use Modules\Asset\Entities\AssetHistory;
use Modules\Asset\Events\AssetLentEvent;
use Modules\Asset\Notifications\AssetLent;
use Modules\Asset\Notifications\AssetReturn;

class AssetHistoryObserver
{

    public function creating(AssetHistory $assetHistory)
    {
        //region Field conditions

        $asset = $assetHistory->asset;

        if ($asset->status == 'lent') {
            // New asset history should not be created if asset is already lent. In this case,
            // prev history entry should be updated first
            throw new ApiException('This asset has already been lent', null, 422, 422, 2014);
        }

        if (auth()->user()) {
            $assetHistory->lender_id = auth()->user()->id;
        }

        //endregion

    }

    public function created(AssetHistory $assetHistory)
    {
        if ($assetHistory->date_of_return === null)
        {
            $assetHistory->user->notify(new AssetLent($assetHistory->asset, $assetHistory));
        }
    }

    public function saving(AssetHistory $assetHistory)
    {
        if (company()) {
            $assetHistory->company_id = company()->id;
        }
    }
    public function saved(AssetHistory $assetHistory)
    {
        $asset = $assetHistory->asset;

        $lentAssetHistory = AssetHistory::whereNull('date_of_return')
            ->where('asset_id', $asset->id)->first();

        if ($lentAssetHistory) {
            // This means the asset has been lent, so, change asset status
            $asset->status = 'lent';
            $asset->save();
        }
        else if ($asset->status !== 'non-functional') {
            $asset->status = 'available';
            $asset->save();
        }
    }

    public function updating(AssetHistory $assetHistory)
    {
        $asset = $assetHistory->asset;

        $prevAssetHistory = AssetHistory::find($assetHistory->id);

        if ($assetHistory->date_of_return == null &&
            $prevAssetHistory->date_of_return !== null && $asset->status == 'lent') {
            // We are trying to create a new lent asset history, which is incorrect
            throw new ApiException('This asset has already been lent', null, 422, 422, 2014);
        }

        if (auth()->user() && $assetHistory->date_of_return !== null && $prevAssetHistory->date_of_return === null) {
            $assetHistory->returner_id = auth()->user()->id;
        }
    }

    public function updated(AssetHistory $assetHistory)
    {
        if ($assetHistory->date_of_return !== null)
        {
            $assetHistory->user->notify(new AssetReturn($assetHistory->asset, $assetHistory));
        }
    }

    public function deleted(AssetHistory $assetHistory)
    {
        AssetHistoryObserver::saved($assetHistory);
    }

}
