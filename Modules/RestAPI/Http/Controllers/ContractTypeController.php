<?php

namespace Modules\RestAPI\Http\Controllers;

use Froiden\RestAPI\ApiController;
use Modules\RestAPI\Entities\ContractType;
use Modules\RestAPI\Http\Requests\ContractType\IndexRequest;
use Modules\RestAPI\Http\Requests\ContractType\CreateRequest;
use Modules\RestAPI\Http\Requests\ContractType\ShowRequest;
use Modules\RestAPI\Http\Requests\ContractType\UpdateRequest;
use Modules\RestAPI\Http\Requests\ContractType\DeleteRequest;

class ContractTypeController extends ApiBaseController
{

    protected $model = ContractType::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = CreateRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $showRequest = ShowRequest::class;
    protected $deleteRequest = DeleteRequest::class;
}
