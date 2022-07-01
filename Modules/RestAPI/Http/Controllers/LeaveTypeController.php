<?php

namespace Modules\RestAPI\Http\Controllers;

use Froiden\RestAPI\ApiController;
use Modules\RestAPI\Entities\LeaveType;
use Modules\RestAPI\Http\Requests\LeaveType\IndexRequest;
use Modules\RestAPI\Http\Requests\LeaveType\CreateRequest;
use Modules\RestAPI\Http\Requests\LeaveType\ShowRequest;
use Modules\RestAPI\Http\Requests\LeaveType\UpdateRequest;
use Modules\RestAPI\Http\Requests\LeaveType\DeleteRequest;

class LeaveTypeController extends ApiBaseController
{

    protected $model = LeaveType::class;

    protected $indexRequest = IndexRequest::class;
    // protected $storeRequest = CreateRequest::class;
    // protected $updateRequest = UpdateRequest::class;
    // protected $showRequest = ShowRequest::class;
    // protected $deleteRequest = DeleteRequest::class;

    public function modifyIndex($query)
    {
        return $query->visibility();
    }
}
