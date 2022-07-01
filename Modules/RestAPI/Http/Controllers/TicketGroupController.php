<?php

namespace Modules\RestAPI\Http\Controllers;

use Modules\RestAPI\Entities\TicketGroup;
use Modules\RestAPI\Http\Requests\TicketGroup\IndexRequest;

class TicketGroupController extends ApiBaseController
{

    protected $model = TicketGroup::class;

    protected $indexRequest = IndexRequest::class;

    public function modifyIndex($query)
    {
        return $query->visibility();
    }
}
