<?php

namespace Modules\RestAPI\Entities;

class ClientDetails extends \App\ClientDetails
{
    protected $hidden = [
        'user_id',
    ];

    protected $guarded = [
        'id',
        'category_id',
        'sub_category_id',
    ];

    protected $filterable = [
        'id',
        'company_name',
        'category_id',
        'sub_category_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(['active']);
    }

    public function category()
    {
        return $this->belongsTo(ClientCategory::class, 'category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(ClientSubCategory::class, 'sub_category_id');
    }
}
