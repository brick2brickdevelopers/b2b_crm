<?php

namespace Modules\RestAPI\Http\Controllers;

use Froiden\RestAPI\ApiController;
use Modules\RestAPI\Entities\Designation;
use Modules\RestAPI\Http\Requests\Designation\IndexRequest;
use Modules\RestAPI\Http\Requests\Designation\CreateRequest;
use Modules\RestAPI\Http\Requests\Designation\UpdateRequest;
use Modules\RestAPI\Http\Requests\Designation\ShowRequest;
use Modules\RestAPI\Http\Requests\Designation\DeleteRequest;

class DesignationController extends ApiBaseController
{
    protected $model = Designation::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = CreateRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $showRequest = ShowRequest::class;
    protected $deleteRequest = DeleteRequest::class;
}
