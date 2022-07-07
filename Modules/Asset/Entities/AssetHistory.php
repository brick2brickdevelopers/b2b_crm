<?php

namespace Modules\Asset\Entities;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Asset\Observers\AssetHistoryObserver;

class AssetHistory extends Model
{
    //region Properties

    protected $table = 'asset_lending_history';

    protected $default = [
        'id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'asset_id',
        'user_id',
        'lender_id',
        'returner_id'
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'asset_id',
        'user_id',
        'lender_id',
        'returner_id'
    ];

    protected $filterable = [
        'id',
        'asset_id',
        'user_id',
        'date_given',
        'return_date',
        'date_of_return'
    ];

    protected $appends = [

    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'date_given',
        'return_date',
        'date_of_return'
    ];

    //endregion

    //region Boot

    public static function boot()
    {
        parent::boot();

        static::observe(AssetHistoryObserver::class);

        $company = company();
        static::addGlobalScope('company', function (Builder $builder) use ($company) {
            if ($company) {
                $builder->where('asset_lending_history.company_id', '=', $company->id);
            }
        });
    }

    //endregion

    //region Relations

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lender()
    {
        return $this->belongsTo(User::class);
    }

    public function returner()
    {
        return $this->belongsTo(User::class);
    }

    //endregion

    //region Custom Functions


}
