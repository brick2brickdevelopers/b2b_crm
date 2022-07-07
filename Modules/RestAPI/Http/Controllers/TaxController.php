<?php

namespace Modules\RestAPI\Http\Controllers;

use Modules\RestAPI\Entities\Tax;
use Modules\RestAPI\Http\Requests\Tax\IndexRequest;

class TaxController extends ApiBaseController
{

    protected $model = Tax::class;

    protected $indexRequest = IndexRequest::class;

    public function modifyIndex($query)
    {
        return $query->visibility();
    }
}
