<?php

namespace Modules\Asset\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Asset\Observers\AssetObserver;

class Asset extends Model
{
    //region Properties

    protected $table = 'assets';
    protected $appends = ['image_url'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'company_id',
        'location_id'
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'company_id'
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
        static::observe(AssetObserver::class);

        $company = company();
        static::addGlobalScope('company', function (Builder $builder) use ($company) {
            if ($company) {
                $builder->where('assets.company_id', '=', $company->id);
            }
        });
    }

    //endregion


    public function history()
    {
        return $this->hasMany(AssetHistory::class);
    }


    public function asset_type()
    {
        return $this->belongsTo(AssetType::class, 'asset_type_id', 'id');
    }

    //endregion

    public function getImageUrlAttribute()
    {
        return ($this->image) ? asset_url('assets/' . $this->image) : 'https://via.placeholder.com/200x150.png?text='.__('asset::app.uploadAssetPicture');
    }
    //region Custom Functions

}
