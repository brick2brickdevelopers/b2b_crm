<?php

namespace Modules\RestAPI\Entities;

use Froiden\RestAPI\ApiModel;
use Modules\RestAPI\Observers\DeviceObserver;

class Device extends ApiModel
{
    //region Properties

    protected $table = 'devices';

    protected $default = [
        'id',
        'device_id',
        'type',
        'status'
    ];

    protected $hidden = [
        'user_id',
        'created_at',
        'updated_at'
    ];

    protected $guarded = [
        'id',
        'user_id'
    ];

    protected $filterable = [
        'id',
        'user_id',
        'type'
    ];

    protected $appends = [

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

        static::observe(DeviceObserver::class);
    }

    //endregion

    //region Custom Functions

    /**
     * Determines if current object is visible to given user
     * @param Employee $user
     * @return bool Weather current object is visible to the given user
     */
    public function visibleTo(Employee $user)
    {
        // Designations are only company wise. So, all designations are
        // visible to everyone in the company

        return $this->user_id == $user->id;
    }

    //endregion

    //region Scopes
}
