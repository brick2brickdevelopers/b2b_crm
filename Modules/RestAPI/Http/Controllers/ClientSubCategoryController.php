<?php

namespace Modules\RestAPI\Http\Controllers;

use Modules\RestAPI\Entities\ClientSubCategory;
use Modules\RestAPI\Http\Requests\ClientSubCategory\IndexRequest;
use Modules\RestAPI\Http\Requests\ClientSubCategory\CreateRequest;
use Modules\RestAPI\Http\Requests\ClientSubCategory\ShowRequest;
use Modules\RestAPI\Http\Requests\ClientSubCategory\UpdateRequest;
use Modules\RestAPI\Http\Requests\ClientSubCategory\DeleteRequest;

class ClientSubCategoryController extends ApiBaseController
{

    protected $model = ClientSubCategory::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = CreateRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $showRequest = ShowRequest::class;
    protected $deleteRequest = DeleteRequest::class;
}
