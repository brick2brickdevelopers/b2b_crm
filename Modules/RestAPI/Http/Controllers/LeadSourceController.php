<?php

namespace Modules\RestAPI\Http\Controllers;

use Modules\RestAPI\Entities\LeadSource;
use Modules\RestAPI\Http\Requests\LeadSource\IndexRequest;
use Modules\RestAPI\Http\Requests\LeadSource\CreateRequest;
use Modules\RestAPI\Http\Requests\LeadSource\ShowRequest;
use Modules\RestAPI\Http\Requests\LeadSource\UpdateRequest;
use Modules\RestAPI\Http\Requests\LeadSource\DeleteRequest;

class LeadSourceController extends ApiBaseController
{

    protected $model = LeadSource::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = CreateRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $showRequest = ShowRequest::class;
    protected $deleteRequest = DeleteRequest::class;
}
