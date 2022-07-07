<?php

namespace Modules\RestAPI\Http\Controllers;

use Modules\RestAPI\Entities\TicketChannel;
use Modules\RestAPI\Http\Requests\TicketChannel\IndexRequest;

class TicketChannelController extends ApiBaseController
{

    protected $model = TicketChannel::class;

    protected $indexRequest = IndexRequest::class;

    public function modifyIndex($query)
    {
        return $query->visibility();
    }
}
