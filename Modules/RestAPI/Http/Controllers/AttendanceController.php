<?php

namespace Modules\RestAPI\Http\Controllers;

use App\AttendanceSetting;
use App\Helper\Reply;
use App\Holiday;
use App\Setting;
use Carbon\Carbon;
use Froiden\RestAPI\ApiResponse;
use Froiden\RestAPI\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;
use Modules\RestAPI\Entities\Attendance;
use Modules\RestAPI\Http\Requests\Attendance\ClockInRequest;
use Modules\RestAPI\Http\Requests\Attendance\ClockOutRequest;
use Modules\RestAPI\Http\Requests\Attendance\CreateRequest;
use Modules\RestAPI\Http\Requests\Attendance\DeleteRequest;
use Modules\RestAPI\Http\Requests\Attendance\IndexRequest;
use Modules\RestAPI\Http\Requests\Attendance\ShowRequest;
use Modules\RestAPI\Http\Requests\Attendance\UpdateRequest;

class AttendanceController extends ApiBaseController
{

    protected $model = Attendance::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = CreateRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $showRequest = ShowRequest::class;
    protected $deleteRequest = DeleteRequest::class;

    protected function modifyIndex($query)
    {
        return $query->groupBy('user_id')->visibility();
    }

    public function today()
    {
        // Getting Attendance setting data
        $attendanceSettings = AttendanceSetting::first();
        $this->global =  Setting::first();

        // Getting Current Clock-in if exist
        $this->currenntClockIn = \App\Attendance::where(
            DB::raw('DATE(clock_in_time)'),
            Carbon::today()->format('Y-m-d')
        )
            ->where('user_id', api_user()->id)->whereNull('clock_out_time')
            ->first();

        // Getting Today's Total Check-ins
        $todayTotalClockin = Attendance::where(DB::raw('DATE(clock_in_time)'), Carbon::today()->format('Y-m-d'))
            ->where('user_id', api_user()->id)
            ->where(DB::raw('DATE(clock_out_time)'), Carbon::today()->format('Y-m-d'))
            ->count();

        //Getting Maximum Check-ins in a day
        $maxAttendanceInDay = $attendanceSettings->clockin_in_day;


        // Check Holiday by date
        $checkTodayHoliday = Holiday::where('date', Carbon::now()->format('Y-m-d'))->first();


        //check office time passed
        $officeEndTime = Carbon::createFromFormat(
            'H:i:s',
            $attendanceSettings->office_end_time,
            $this->global->timezone
        )->timestamp;
        $currentTime = Carbon::now()->timezone($this->global->timezone)->timestamp;

        if ($checkTodayHoliday) {
            throw new ApiException('Today is holiday', null, 422, 422, 2001);
        } elseif ($todayTotalClockin >= $maxAttendanceInDay) {
            throw new ApiException('Maximum check-ins reached', null, 422, 422, 2000);
        }

        $result['attendance'] = $this->currenntClockIn;
        $result['office_hours_passed'] = false;
        $result['time'] = Carbon::now()->format('c');
        $result['ip_address'] = $_SERVER['REMOTE_ADDR'];
        $result['remaining_clock_in'] = $maxAttendanceInDay-$todayTotalClockin;

        return ApiResponse::make(null, $result);
    }

    public function clockIn()
    {
        $now = Carbon::now();
        $this->user = api_user();
        $this->global =  Setting::first();
        $attendanceSettings = AttendanceSetting::first();
        $clockInCount = \App\Attendance::getTotalUserClockIn($now->format('Y-m-d'), $this->user->id);


        // Check user by ip
        if ($attendanceSettings->ip_check == 'yes') {
            $ips = (array) json_decode($attendanceSettings->ip_address);
            if (!in_array(request()->ip(), $ips)) {
                return Reply::error(__('messages.notAnAuthorisedDevice'));
            }
        }

        // Check user by location
        if ($attendanceSettings->radius_check == 'yes') {
            $checkRadius  = $this->isWithinRadius(request());
            if (!$checkRadius) {
                return Reply::error(__('messages.notAnValidLocation'));
            }
        }

        // Check maximum attendance in a day
        if ($clockInCount < $attendanceSettings->clockin_in_day) {
            // Set TimeZone And Convert into timestamp
            $currentTimestamp = $now->setTimezone('UTC');
            $currentTimestamp = $currentTimestamp->timestamp;
            ;

            // Set TimeZone And Convert into timestamp in halfday time
            if ($attendanceSettings->halfday_mark_time) {
                $halfDayTimestamp = $now->format('Y-m-d') . ' ' . $attendanceSettings->halfday_mark_time;
                $halfDayTimestamp = Carbon::createFromFormat('Y-m-d H:i:s', $halfDayTimestamp, $this->global->timezone);
                $halfDayTimestamp = $halfDayTimestamp->setTimezone('UTC');
                $halfDayTimestamp = $halfDayTimestamp->timestamp;
            }


            $timestamp = $now->format('Y-m-d') . ' ' . $attendanceSettings->office_start_time;
            $officeStartTime = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, $this->global->timezone);
            $officeStartTime = $officeStartTime->setTimezone('UTC');


            $lateTime = $officeStartTime->addMinutes($attendanceSettings->late_mark_duration);

            $checkTodayAttendance = Attendance::where('user_id', $this->user->id)
                ->where(DB::raw('DATE(attendances.clock_in_time)'), '=', $now->format('Y-m-d'))->first();

            $attendance = new Attendance();
            $attendance->user_id = $this->user->id;
            $attendance->clock_in_time = $now;
            $attendance->clock_in_ip = request()->ip();

            if (is_null(request()->working_from)) {
                $attendance->working_from = 'office';
            } else {
                $attendance->working_from = request()->working_from;
            }

            if ($now->gt($lateTime) && is_null($checkTodayAttendance)) {
                $attendance->late = 'yes';
            }

            $attendance->half_day = 'no'; // default halfday

            // Check day's first record and half day time
            if (!is_null($attendanceSettings->halfday_mark_time)
                && is_null($checkTodayAttendance)
                && $currentTimestamp > $halfDayTimestamp
            ) {
                $attendance->half_day = 'yes';
            }

            $attendance->save();

            return ApiResponse::make('Clocked in successfully', [
                'time' => $attendance->clock_in_time
            ]);
        }
    }

    public function clockOut($id)
    {
        $now = Carbon::now();
        $this->user = api_user();
        $attendanceSettings = AttendanceSetting::first();
        $attendance = \App\Attendance::findOrFail($id);

        // Check user by ip
        if ($attendanceSettings->ip_check == 'yes') {
            $ips = (array) json_decode($attendanceSettings->ip_address);
            if (!in_array(request()->ip(), $ips)) {
                return Reply::error(__('messages.notAnAuthorisedDevice'));
            }
        }

        // Check user by location
        if ($attendanceSettings->radius_check == 'yes') {
            $checkRadius  = $this->isWithinRadius(request());
            if (!$checkRadius) {
                return Reply::error(__('messages.notAnValidLocation'));
            }
        }

        $attendance->clock_out_time = $now;
        $attendance->clock_out_ip = request()->ip();
        $attendance->save();

        return ApiResponse::make('Clocked out successfully', [
            'time' => $attendance->clock_out_ip
        ]);
    }

    private function isWithinRadius($request)
    {
        $this->global =  Setting::first();
        $attendanceSettings = AttendanceSetting::first();
        $radius = $attendanceSettings->radius;
        $currentLatitude = request()->currentLatitude;
        $currentLongitude = request()->currentLongitude;
        // $requestCoordinates = $this->checkFromIp($request);

        $latFrom = deg2rad($this->global->latitude);
        $latTo = deg2rad($currentLatitude);

        $lonFrom = deg2rad($this->global->longitude);
        $lonTo = deg2rad($currentLongitude);

        $theta = $lonFrom - $lonTo;

        $dist = sin($latFrom) * sin($latTo) +  cos($latFrom) * cos($latTo) * cos($theta);
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $distance = $dist * 60 * 1.1515 * 1609.344;
        return $distance <= $radius;
    }
}
