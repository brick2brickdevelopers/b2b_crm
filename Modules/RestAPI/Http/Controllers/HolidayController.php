<?php

namespace Modules\RestAPI\Http\Controllers;

use Froiden\RestAPI\ApiController;
use Modules\RestAPI\Entities\Holiday;
use Modules\RestAPI\Http\Requests\Holiday\IndexRequest;
use Modules\RestAPI\Http\Requests\Holiday\CreateRequest;
use Modules\RestAPI\Http\Requests\Holiday\ShowRequest;
use Modules\RestAPI\Http\Requests\Holiday\UpdateRequest;
use Modules\RestAPI\Http\Requests\Holiday\DeleteRequest;

class HolidayController extends ApiBaseController
{

    protected $model = Holiday::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = CreateRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $showRequest = ShowRequest::class;
    protected $deleteRequest = DeleteRequest::class;
}
