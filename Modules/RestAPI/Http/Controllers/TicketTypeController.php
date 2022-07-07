<?php

namespace Modules\RestAPI\Http\Controllers;

use Modules\RestAPI\Entities\TicketType;
use Modules\RestAPI\Http\Requests\TicketType\IndexRequest;

class TicketTypeController extends ApiBaseController
{

    protected $model = TicketType::class;

    protected $indexRequest = IndexRequest::class;

    public function modifyIndex($query)
    {
        return $query->visibility();
    }
}
