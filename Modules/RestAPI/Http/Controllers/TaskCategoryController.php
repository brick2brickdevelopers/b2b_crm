<?php

namespace Modules\RestAPI\Http\Controllers;

use Modules\RestAPI\Entities\TaskCategory;
use Modules\RestAPI\Http\Requests\TaskCategory\IndexRequest;
use Modules\RestAPI\Http\Requests\TaskCategory\CreateRequest;
use Modules\RestAPI\Http\Requests\TaskCategory\ShowRequest;
use Modules\RestAPI\Http\Requests\TaskCategory\UpdateRequest;
use Modules\RestAPI\Http\Requests\TaskCategory\DeleteRequest;

class TaskCategoryController extends ApiBaseController
{

    protected $model = TaskCategory::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = CreateRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $showRequest = ShowRequest::class;
    protected $deleteRequest = DeleteRequest::class;
}
