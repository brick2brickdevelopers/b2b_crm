<?php

namespace Modules\RestAPI\Entities;

class ClientSubCategory extends \App\ClientSubCategory
{
    protected $fillable = ['category_name'];

    protected $default = ['id', 'category_name', 'category_id'];

    protected $guarded = ['id', 'category_id'];

    protected $filterable = ['category_name', 'category_id'];

    public function category()
    {
        return $this->belongsTo(ClientCategory::class, 'category_id');
    }
}
