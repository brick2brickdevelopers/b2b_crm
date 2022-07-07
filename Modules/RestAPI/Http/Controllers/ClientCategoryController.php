<?php

namespace Modules\RestAPI\Http\Controllers;

use Modules\RestAPI\Entities\ClientCategory;
use Modules\RestAPI\Http\Requests\ClientCategory\IndexRequest;
use Modules\RestAPI\Http\Requests\ClientCategory\CreateRequest;
use Modules\RestAPI\Http\Requests\ClientCategory\ShowRequest;
use Modules\RestAPI\Http\Requests\ClientCategory\UpdateRequest;
use Modules\RestAPI\Http\Requests\ClientCategory\DeleteRequest;

class ClientCategoryController extends ApiBaseController
{

    protected $model = ClientCategory::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = CreateRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $showRequest = ShowRequest::class;
    protected $deleteRequest = DeleteRequest::class;
}
