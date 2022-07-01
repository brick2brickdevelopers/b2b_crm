<?php

namespace Modules\RestAPI\Http\Controllers;

use Carbon\Carbon;
use Froiden\RestAPI\ApiResponse;
use Modules\RestAPI\Entities\Event;
use Modules\RestAPI\Entities\Holiday;
use Modules\RestAPI\Http\Requests\Event\IndexRequest;
use Modules\RestAPI\Http\Requests\Event\CreateRequest;
use Modules\RestAPI\Http\Requests\Event\ShowRequest;
use Modules\RestAPI\Http\Requests\Event\UpdateRequest;
use Modules\RestAPI\Http\Requests\Event\DeleteRequest;

class EventController extends ApiBaseController
{

    protected $model = Event::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = CreateRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $showRequest = ShowRequest::class;
    protected $deleteRequest = DeleteRequest::class;

    public function me()
    {
        app()->make($this->indexRequest);

        $startMonth =  2; // Add 1 because in moment months start from 0, but in carbon they start from 1
        $now = Carbon::now();

        $startYear = ($now->month < $startMonth) ? $now->year - 1 : $now->year;

        $startDate = Carbon::now()->year($startYear)->month($startMonth)->startOfMonth()->format('Y-m-d H:i:s');
        $endDate = Carbon::now()
            ->year($startYear)
            ->month($startMonth)
            ->startOfMonth()
            ->addYear(2)
            ->subDay(1)
            ->format('Y-m-d H:i:s');

        $query = Holiday::select('id', 'date', 'occassion');

        // Modify query
        $query->where('holidays.date', '>=', $startDate);

        $holidays = $query->get()->toArray();


        $events = Event::with('attendees')
            ->select('events.id', 'event_name', 'start_date_time', 'end_date_time', 'where', 'label_color');
        // Modify query
        $events->join('event_attendees', 'event_attendees.event_id', '=', 'events.id')
            ->where('events.start_date_time', '>=', $startDate)
            ->where('events.end_date_time', '<=', $endDate)->groupBy('event_attendees.event_id');

        $events = $events->get()->toArray();

        $results = [
            'holidays' => $holidays,
            'events' => $events,
        ];

        return ApiResponse::make(null, $results);
    }
}
