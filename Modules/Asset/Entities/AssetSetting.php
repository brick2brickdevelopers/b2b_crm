<?php

namespace Modules\Asset\Entities;

use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Modules\Asset\Observers\AssetObserver;
use Modules\Asset\Observers\AssetSettingObserver;

class AssetSetting extends Model
{

    //region Properties

    protected $table = 'asset_settings';


    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];


    protected $dates = [
        'created_at',
        'updated_at'
    ];

    //endregion


    public function history()
    {
        return $this->hasMany(AssetHistory::class);
    }

    //endregion

    //region Custom Functions

}
