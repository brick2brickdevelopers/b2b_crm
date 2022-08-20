<?php

namespace Modules\RestAPI\Http\Controllers;

use App\Event;
use App\EventAttendee;
use App\GoogleAccount;
use App\Notification;
use App\Notifications\EventInvite;
use App\Services\Google;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;


class MeetingController extends ApiBaseController
{
   


    public function event_store(Request $request)

    {
        $request->validate([
            'event_name' => 'required',
            'start_date_time' => 'required',
            'end_date_time' => 'required',
            'description' => 'required',
            'where' => 'required',
        ]);
       
       
        $eventIds = [];
        $event = new Event();
        $event->event_name = $request->event_name;
        $event->where = $request->where;
        $event->description = $request->description;
        $event->category_id = $request->category_id;
        $event->event_type_id = $request->event_type_id;
        $event->event_unique_id = $request->event_unique_id;


        $event->start_date_time =  $request->start_date_time;
        $event->end_date_time =  $request->end_date_time;
        if ($request->repeat) {
            $event->repeat = $request->repeat;
        } else {
            $event->repeat = 'no';
        }

        $event->repeat_every = $request->repeat_count;
        $event->repeat_cycles = $request->repeat_cycles;
        $event->repeat_type = $request->repeat_type;
        $event->label_color = $request->label_color;
        $event->created_by = Auth::user()->id;
        $event->lead_id = json_encode($request->lead_id);
        $event->save();
        $eventIds [] = $event->id;
        if ($request->all_employees) {
            $attendees = User::allEmployees();
            foreach ($attendees as $attendee) {
              
                EventAttendee::create(['user_id' => $attendee->id, 'event_id' => $event->id]);
            }
           

            // Notification::send($attendees, new EventInvite($event));
        }
        
        if ($request->all_clients) {
            if(isset($attendees)){
                $attendees = User::allClients()->merge($attendees);
            }
            else{
                $attendees = User::allClients();
            }
            foreach ($attendees as $attendee) {
                EventAttendee::create(['user_id' => $attendee->id, 'event_id' => $event->id]);
            }
          //  Notification::send($attendees, new EventInvite($event));

           
        }
        if(empty($request->all_employees))
        {
            EventAttendee::create(['user_id' => Auth::user()->id, 'event_id' => $event->id]);

           
        }
       
        if ($request->user_id == null) {
            foreach ($request->user_id as $userId) {
                EventAttendee::firstOrCreate(['user_id' => $userId, 'event_id' => $event->id]);
            }
            $attendees = User::whereIn('id', $request->user_id)->get();
          //  Notification::send($attendees, new EventInvite($event));
          
        }
        if (!$request->has('repeat') || $request->repeat == 'no') {
            $event->event_id = $this->googleCalendarEvent($event);
            $event->save();
        }
        // Add repeated event
        if ($request->has('repeat') && $request->repeat == 'yes') {
            $repeatCount = $request->repeat_count;
            $repeatType = $request->repeat_type;
            $repeatCycles = $request->repeat_cycles;

            $startDate = Carbon::createFromFormat($this->global->date_format, $request->start_date);
            $dueDate = Carbon::createFromFormat($this->global->date_format, $request->end_date);

            for ($i = 1; $i < $repeatCycles; $i++) {
                $startDate = $startDate->add($repeatCount, str_plural($repeatType));
                $dueDate = $dueDate->add($repeatCount, str_plural($repeatType));

                $event = new Event();
                $event->event_name = $request->event_name;
                $event->where = $request->where;
                $event->description = $request->description;
                $event->start_date_time = $startDate->format('Y-m-d') . ' ' . Carbon::parse($request->start_time)->format('H:i:s');
                $event->end_date_time = $dueDate->format('Y-m-d') . ' ' . Carbon::parse($request->end_time)->format('H:i:s');
                $event->event_unique_id = $request->event_unique_id;

                if ($request->repeat) {
                    $event->repeat = $request->repeat;
                } else {
                    $event->repeat = 'no';
                }
                $event->repeat_every = $request->repeat_count;
                $event->repeat_cycles = $request->repeat_cycles;
                $event->repeat_type = $request->repeat_type;
                $event->label_color = $request->label_color;
                $event->save();

                if ($request->all_employees) {
                    $attendees = User::allEmployees();
                    foreach ($attendees as $attendee) {
                        EventAttendee::create(['user_id' => $attendee->id, 'event_id' => $event->id]);
                    }
        
                //    Notification::send($attendees, new EventInvite($event));
                }
        
                if ($request->user_id) {
                    foreach ($request->user_id as $userId) {
                        
                        EventAttendee::firstOrCreate(['user_id' => $userId, 'event_id' => $event->id]);
                    }
                    $attendees = User::whereIn('id', $request->user_id)->get();
                 //   Notification::send($attendees, new EventInvite($event));
                }
                $eventIds [] = $event->id;
            }
            $this->googleCalendarEventMulti($eventIds);
        }

            $data = Event::all();
            return response()->json([
                'success'     => true,
                'status'      => 200,
                'message'     => "Event Create successfully",
                'event' =>  $data,
            ]);
        
    }

    protected function googleCalendarEvent($event)
    {

        if (company() && global_settings()->google_calendar_status == 'active') {

            $google = new Google();
            $company = company();
            $attendiesData = [];

            $attendees = EventAttendee::with(['user'])->where('event_id', $event->id)->get();

            foreach($attendees as $attend){
                if(!is_null($attend->user) && !is_null($attend->user->email) && !is_null($attend->user->calendar_module) && $attend->user->calendar_module->event_status)
                {
                    $attendiesData[] = ['email' => $attend->user->email];
                }
            }

            $googleAccount = GoogleAccount::where('company_id', company()->id)->first();;
            if ((global_settings()->google_calendar_status == 'active') && $googleAccount) {

                $description = __('email.newEvent.subject');
                $description = $event->event_name . ' :- ' . $description;
                $description = $event->event_name . ' :- ' . $description . ' ' . $event->description;

                // Create event
                $google = $google->connectUsing($googleAccount->token);

                $eventData = new \Google_Service_Calendar_Event(array(
                    'summary' => $event->event_name,
                    'location' => $event->where,
                    'description' => $description,
                    'start' => array(
                        'dateTime' => $event->start_date_time,
                        'timeZone' => $company->timezone,
                    ),
                    'end' => array(
                        'dateTime' => $event->end_date_time,
                        'timeZone' => $company->timezone,
                    ),
                    'attendees' => $attendiesData,
                    'colorId' => 7,
                    'reminders' => array(
                        'useDefault' => false,
                        'overrides' => array(
                            array('method' => 'email', 'minutes' => 24 * 60),
                            array('method' => 'popup', 'minutes' => 10),
                        ),
                    ),
                ));

                try {
                    if ($event->event_id) {
                        $results = $google->service('Calendar')->events->patch('primary', $event->event_id, $eventData);
                    } else {
                        $results = $google->service('Calendar')->events->insert('primary', $eventData);
                    }

                    return $results->id;
                } catch (\Google\Service\Exception $th) {
                    $googleAccount->delete();
                    $google->revokeToken($googleAccount->token);
                }
            }
            return $event->event_id;
        }
    }
    
}
