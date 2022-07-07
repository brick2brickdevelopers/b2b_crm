<?php

namespace Modules\RestAPI\Http\Controllers;

use Carbon\Carbon;
use Froiden\RestAPI\ApiResponse;
use Modules\RestAPI\Entities\ProjectTimeLog;
use Modules\RestAPI\Http\Requests\TimeLog\IndexRequest;
use Modules\RestAPI\Http\Requests\TimeLog\CreateRequest;
use Modules\RestAPI\Http\Requests\TimeLog\UpdateRequest;

class TimeLogController extends ApiBaseController
{
    protected $model = ProjectTimeLog::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = CreateRequest::class;
    protected $updateRequest = UpdateRequest::class;

    public function modifyIndex($query)
    {
        return $query->visibility();
    }

    public function storing(ProjectTimeLog $projectTimeLog)
    {
        $projectTimeLog->user_id = api_user()->id;
        $projectTimeLog->start_time = Carbon::now();
        return $projectTimeLog;
    }

    public function updating(ProjectTimeLog $projectTimeLog)
    {
        $startTime = $projectTimeLog->start_time;
        $endTime = Carbon::now();
        $totalHours = $endTime->diff($startTime)->format('%d') * 24 + $endTime->diff($startTime)->format('%H');

        $projectTimeLog->total_hours = $totalHours;
        $projectTimeLog->total_minutes = ($totalHours * 60) + ($endTime->diff($startTime)->format('%i'));
        $projectTimeLog->edited_by_user = api_user()->id;
        $projectTimeLog->end_time = $endTime;

        return $projectTimeLog;
    }

    public function me()
    {
        app()->make($this->indexRequest);

        $query = $this->parseRequest()
            ->addIncludes()
            ->addFilters()
            ->addOrdering()
            ->addPaging()
            ->getQuery();

        $user = api_user();

        $query->with('task')
            ->whereNull('end_time')
            ->where('user_id', $user->id);

        // Load employees relation, if not loaded
        $relations = $query->getEagerLoads();

        $relationRequested = true;

        $query->setEagerLoads($relations);

        /** @var Collection $results */
        $results = $this->getResults();

        $results = $results->toArray();
        $results = $results ? $results[0] : [];

        $meta = $this->getMetaData();

        return ApiResponse::make(null, $results, $meta);
    }
}
