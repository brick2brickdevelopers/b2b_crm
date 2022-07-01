<?php

namespace Modules\RestAPI\Http\Controllers;

use Froiden\RestAPI\ApiController;
use Modules\RestAPI\Entities\Contract;
use Modules\RestAPI\Http\Requests\Contract\IndexRequest;
use Modules\RestAPI\Http\Requests\Contract\CreateRequest;
use Modules\RestAPI\Http\Requests\Contract\ShowRequest;
use Modules\RestAPI\Http\Requests\Contract\UpdateRequest;
use Modules\RestAPI\Http\Requests\Contract\DeleteRequest;

class ContractController extends ApiBaseController
{

    protected $model = Contract::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = CreateRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $showRequest = ShowRequest::class;
    protected $deleteRequest = DeleteRequest::class;
}
