<?php

namespace Modules\RestAPI\Http\Controllers;

use Modules\RestAPI\Entities\SubTask;
use Modules\RestAPI\Http\Requests\SubTask\IndexRequest;
use Modules\RestAPI\Http\Requests\SubTask\CreateRequest;
use Modules\RestAPI\Http\Requests\SubTask\ShowRequest;
use Modules\RestAPI\Http\Requests\SubTask\UpdateRequest;
use Modules\RestAPI\Http\Requests\SubTask\DeleteRequest;

class SubTaskController extends ApiBaseController
{
    protected $model =  SubTask::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = CreateRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $showRequest = ShowRequest::class;
    protected $deleteRequest = DeleteRequest::class;

    protected function modifyShow($query)
    {
        return $query->where('task_id', request()->route('task_id'));
    }

    public function storing(SubTask $subTask)
    {
        $subTask->task_id = request()->route('task_id');
        return $subTask;
    }
    protected function modifyUpdate($query)
    {
        return $query->where('task_id', request()->route('task_id'));
    }

    protected function modifyIndex($query)
    {
        return $query->where('task_id', request()->route('task_id'));
    }
}
