<?php

namespace Modules\RestAPI\Http\Controllers;

use Froiden\RestAPI\ApiController;
use Modules\RestAPI\Entities\Department;
use Modules\RestAPI\Http\Requests\Department\IndexRequest;
use Modules\RestAPI\Http\Requests\Department\CreateRequest;
use Modules\RestAPI\Http\Requests\Department\UpdateRequest;
use Modules\RestAPI\Http\Requests\Department\ShowRequest;
use Modules\RestAPI\Http\Requests\Department\DeleteRequest;

class DepartmentController extends ApiBaseController
{
    protected $model = Department::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = CreateRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $showRequest = ShowRequest::class;
    protected $deleteRequest = DeleteRequest::class;
}
