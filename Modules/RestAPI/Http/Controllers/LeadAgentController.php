<?php

namespace Modules\RestAPI\Http\Controllers;

use Modules\RestAPI\Entities\LeadAgent;
use Modules\RestAPI\Http\Requests\LeadAgent\IndexRequest;
use Modules\RestAPI\Http\Requests\LeadAgent\CreateRequest;
use Modules\RestAPI\Http\Requests\LeadAgent\ShowRequest;
use Modules\RestAPI\Http\Requests\LeadAgent\UpdateRequest;
use Modules\RestAPI\Http\Requests\LeadAgent\DeleteRequest;

class LeadAgentController extends ApiBaseController
{

    protected $model = LeadAgent::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = CreateRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $showRequest = ShowRequest::class;
    protected $deleteRequest = DeleteRequest::class;
}
