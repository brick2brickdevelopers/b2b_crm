<?php

namespace Modules\RestAPI\Http\Controllers;

use Modules\RestAPI\Entities\LeadStatus;
use Modules\RestAPI\Http\Requests\LeadStatus\IndexRequest;
use Modules\RestAPI\Http\Requests\LeadStatus\CreateRequest;
use Modules\RestAPI\Http\Requests\LeadStatus\ShowRequest;
use Modules\RestAPI\Http\Requests\LeadStatus\UpdateRequest;
use Modules\RestAPI\Http\Requests\LeadStatus\DeleteRequest;

class LeadStatusController extends ApiBaseController
{

    protected $model = LeadStatus::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = CreateRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $showRequest = ShowRequest::class;
    protected $deleteRequest = DeleteRequest::class;
}
