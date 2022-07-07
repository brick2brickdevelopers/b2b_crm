<?php

namespace Modules\Asset\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Asset\Observers\AssetObserver;
use Modules\Asset\Observers\AssetTypeObserver;

class AssetType extends Model
{
    //region Properties

    protected $table = 'asset_types';


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

    //region Boot

    public static function boot()
    {
        parent::boot();
        static::observe(AssetTypeObserver::class);

        $company = company();
        static::addGlobalScope('company', function (Builder $builder) use ($company) {
            if ($company) {
                $builder->where('asset_types.company_id', '=', $company->id);
            }
        });
    }

    //endregion


    public function history()
    {
        return $this->hasMany(AssetHistory::class);
    }

    //endregion

    //region Custom Functions

}
