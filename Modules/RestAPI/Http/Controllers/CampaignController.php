<?php

namespace Modules\RestAPI\Http\Controllers;

use App\Callingdata;
use App\CallPurpose;
use App\Campaign;
use App\CampaignAgent;
use App\CampaignLead;
use App\EmployeeDetails;
use App\EmployeeTeam;
use App\ClientDetails;
use App\Helper\Reply;
use App\Team;
use App\Lead;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\CustomField;
use App\CustomFieldGroup;
use App\ManualLoggedCall;
use App\AttendanceSetting;
use App\CallOutcome;
use App\CampaignLeadStatus;
use App\Holiday;
use App\Setting;
use App\TaskboardColumn;
use App\TaskUser;
use Froiden\RestAPI\ApiResponse;
use Froiden\RestAPI\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;
use Modules\RestAPI\Entities\Attendance;
use Modules\RestAPI\Entities\Invoice;
use Modules\RestAPI\Entities\Project;
use Modules\RestAPI\Entities\Task;

use App\Event;
use App\EventAttendee;
use App\GoogleAccount;
use App\Http\Requests\Events\StoreEvent;
use App\Http\Requests\Events\UpdateEvent;
use App\Notifications\EventInvite;
use App\Services\Google;
use App\User;
use App\EventCategory;
use App\EventType;

use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;






class CampaignController extends ApiBaseController
{

    //list of all the campaign
    public function index()
    {
    }


    //all campign list
    public function campaign_list(Request $request)
    {

        $user      = auth('api')->user();
        $userId    = $user->id;
        $perPage   = $request->page_size;


        $campaign = Campaign::join('campaign_leads', 'campaign_leads.campaign_id', '=', 'campaigns.id')
            ->where('campaign_leads.agent_id', $userId)
            ->select('campaigns.*')
            ->groupby('campaigns.id')
            ->orderBy('campaigns.id', 'desc')
            ->paginate($perPage);
        if (count($campaign) > 0) {
            $campaign = $campaign;
        } else {
            $campaign = [];
        }
        return response()->json([
            'success'  => true,
            'status'   => 200,
            'code'     => "success",
            'message'  => "Campaign retrieved successfully",
            'campaign' =>  $campaign
            //  'count'    => $data 
        ]);
    }

    //list of lead assingned to particular user
    public function user_lead(Request $request)
    {
        $user    = auth('api')->user();
        $userId  = $user->id;
        $campaign = Campaign::where('id', $request->campaign_id)->get();
        $leadStatusText = '';
        $callStatus = $request->leadcallstatus;
        if ($callStatus === '0') {
            $callStatus = ['0'];
            $leadStatusText = 'Available';
        }
        if ($callStatus == 1) {
            $callStatus = [1];
            $leadStatusText = 'completed';
        }
        if ($callStatus == 2) {
            $callStatus = [2];
            $leadStatusText = 'folow up';
        }
        if (!$callStatus) {
            $callStatus = [0, 1, 2];
        }


        $orders = CampaignLead::where(['campaign_id' => $request->campaign_id, 'agent_id' => $userId])
            ->whereIn('leadcallstatus', $callStatus)
            ->get();
        $leadId = [];
        foreach ($orders as $order) {
            array_push($leadId, $order['lead_id']);
        }


        $perPage = $request->page_size;

        $clientInfo = Lead::whereIn('id', $leadId)->paginate($perPage);
        $abc = [];
        if (count($clientInfo) > 0) {
            $clientInfo = $clientInfo;


            //  foreach ($clientInfo as $cli) {

            //      if ($cli['status_id'] == 4) {
            //          $cli['leadCallStatus'] = 'Available';
            //      }
            //      if ($cli['status_id'] == 1) {
            //          $cli['leadCallStatus'] = 'Completed';
            //      }
            //      if ($cli['status_id'] == 2) {
            //          $cli['leadCallStatus'] = 'follow up';
            //      }
            //  }


        } else {
            $clientInfo = [];
        }

        return response()->json([
            'success'  => true,
            'status'   => 200,
            'code'     => "success",
            'message'  => "Lead retrieved successfully",
            'campaign_info' => $campaign,
            'lead' => $clientInfo
        ]);
    }

    //update the particular lead status based on lead id , user id and compaign_id
    public function update_lead_status(Request $request)
    {
        $user    = auth('api')->user();
        $userId  = $user->id;

        CampaignLead::where(['campaign_id' => $request->campaign_id, 'agent_id' => $userId, 'lead_id' => $request->lead_id])
            ->update(['status' => $request->status]);
        $updatedLead = Lead::where('id', $request->lead_id)->get();

        return response()->json([
            'success' => 'true',
            'status' => 200,
            'code' => "success",
            'message' => 'Lead status has been updated successfully',
            'Updatedlead' => $updatedLead
        ]);
    }

    public function getDynamicOptions()
    {
        return response()->json([
            'success'  => true,
            'status'   => 200,
            'code'     => "success",
            'message'  => "call disposal has been initiated successfully",
            'data'     =>  [
                'outcomes' => CallOutcome::all(),
                'campaignLeadStatus' => CampaignLeadStatus::all(),
                'callPurpose' =>  CallPurpose::all(),
            ],
        ]);
    }

    //call purpose api
    public function call_purpose(Request $request)
    {
        $perPage = $request->page_size;
        $data = CallPurpose::orderBy('id', 'desc')->paginate($perPage);
        return response()->json([
            'success'     => true,
            'status'      => 200,
            'message'     => "call Purpose data has been fetched successfully",
            'callPurpose' =>  $data,
        ]);
    }


    //call disposal api
    public function call_disposal(Request $request)
    {
        $request->validate([
            // 'lead_name' => 'required',
            // 'lead_email' => 'required',
            'call_status' => 'required',
            'call_type' => 'required',
            'call_source' => 'required',
            'call_outcome_id' => 'required',
            'campaign_lead_status_id' => 'required',
            // 'lead_id' => 'required',
            'campaign_id' => 'required',
            'lead_number' => 'required',
            // 'duration' => 'required',
        ]);

        $user           = auth('api')->user();
        $userId         = $user->id;
        $agentNumber    = $user->mobile;
        $campaignId     = $request->campaign_id;
        $leadId         = $request->lead_id;
        $callPurpose    = $request->call_purpose;
        $leadMobile     = $request->lead_number;
        $callStatus     = $request->call_status;
        $callType       = $request->call_type;
        $callSource     = $request->call_source;
        $leadcallstatus = $request->leadcallstatus;
        $call_outcome        = $request->call_outcome_id;
        $duration = $request->duration;
        $campaign_lead_status        = $request->campaign_lead_status_id;

        if (!$agentNumber) {
            $agentNumber = 12345678;
        }

        $leadName  = $request->lead_name;
        $leadEmail = $request->lead_email;

        //create Lead
        $new_lead = Lead::where('mobile', $leadMobile)->count();
        if ($new_lead == 0) {
            $lead = new Lead();
            $lead->company_id = $user->company_id;
            $lead->client_name = $leadName;
            $lead->client_email = $leadEmail;
            $lead->mobile = $leadMobile;
            $lead->save();

            $leadId = $lead->id;
        } else {
            Lead::where('id', $leadId)->update(array('client_name' => $leadName, 'client_email' => $leadEmail));
        }


        // $duration = '00:00:00';
        // $recordingsFile
        $manualLoggedCallData = ManualLoggedCall::create([
            'company_id'       => $user->company_id,
            'lead_id'          => $leadId,
            'lead_number'      => $leadMobile,
            'agent_number'     => $agentNumber,
            'created_by'       => $userId,
            'call_status' => $callStatus,
            'call_outcome_id'          => $call_outcome,
            'campaign_lead_status_id'          => $campaign_lead_status,
            'duration'         => $duration,
            'status'           => $leadcallstatus,
            'call_type'        => $callType,
            'call_source'      => $callSource,
            'call_purpose'     => $callPurpose,
            'campaign_id'      => $campaignId,
            'session_id' =>  uniqid()
        ]);


        // //store the call_purpose and user_id
        // $callPurpose  = CallPurpose::create([
        //     'company_id' => $user->company_id,
        //     'purpose' => $callPurpose,
        //     'from_id' =>  $userId,
        // ]);

        CampaignLead::where('lead_id', $leadId)->update(array('leadcallstatus' => $leadcallstatus));
        //update the lead status 
        Lead::where('id', $leadId)->update(array('status_id' => $leadcallstatus));
        //get single lead data
        $leadRecord =  Lead::where('id', $leadId)->first();

        $data = [];

        if (
            $leadcallstatus == 0
        ) {
            $updatedLeadStatus = 'Available';
        } elseif ($leadcallstatus == 1) {
            $updatedLeadStatus = 'Completed';
        } else {
            $updatedLeadStatus = 'Follow Up';
        }
        $newData = array(
            'lead_id'         =>  $leadId,
            'campaign_id'     =>  $campaignId,
            'lead_mobile'     =>  $leadMobile,
            'lead_name'       =>  $leadRecord->client_name,
            'lead_email'      =>  $leadRecord->client_email,
            // 'agent_id'        => $userId,
            // 'agent_mobile'    => $agentNumber,
            // 'purpose'         =>  $callPurpose,
            'leadcall_status' => $updatedLeadStatus
        );
        array_push($data, $newData);
        return response()->json([
            'success'  => true,
            'status'   => 200,
            'code'     => "success",
            'message'  => "call disposal has been initiated successfully",
            'data'     =>  $data,
            // 'leadData' => $leadRecord
        ]);
    }

    public function employee_dashboard(Request $request)
    {
        $user    = auth('api')->user();
        $userId  = $user->id;
        $data = [];
        $contactBySource = [];
        $totalLeads             = CampaignLead::where('agent_id', $userId)->count();
        $availableForCallLeads  = CampaignLead::where(['agent_id' => $userId, 'leadcallstatus' => 0])->count();
        $completedLeads         = CampaignLead::where(['agent_id' => $userId, 'leadcallstatus' => 1])->count();
        $followUpLeads          = CampaignLead::where(['agent_id' => $userId, 'leadcallstatus' => 2])->count();
        // $userTask               = Task::where('user_id', $request->user_id)->count();

        $otherLeadCount         = Lead::where(['agent_id' => $userId, 'source_id' => 38])->count();
        $emailLeadCount         = Lead::where(['agent_id' => $userId, 'source_id' => 1])->count();
        $googleLeadCount        = Lead::where(['agent_id' => $userId, 'source_id' => 2])->count();
        $facebookLeadCount      = Lead::where(['agent_id' => $userId, 'source_id' => 3])->count();
        $friendLeadCount        = Lead::where(['agent_id' => $userId, 'source_id' => 4])->count();
        $directVisitLeadCount   = Lead::where(['agent_id' => $userId, 'source_id' => 5])->count();
        $tvAdLeadCount          = Lead::where(['agent_id' => $userId, 'source_id' => 6])->count();


        $taskBoardColumn = TaskboardColumn::all();
        $completedTaskColumn = $taskBoardColumn->filter(function ($value, $key) {
            return $value->slug == 'completed';
        })->first();

        $totalProjects = Project::select('projects.id')
            ->join('project_members', 'project_members.project_id', '=', 'projects.id')
            ->where('project_members.user_id', '=', $userId)
            ->groupBy('projects.id')
            ->get()
            ->count();


        $userTask = Task::where('created_by', $userId)
            ->limit(4)->get();
        $taskIds = [];
        foreach ($userTask as $task) {
            array_push($taskIds, $task['id']);
        }
        //per page

        $ldate      = date('Y-m-d');
        $startDate  = Carbon::createFromFormat('Y-m-d', $ldate)->startOfDay();

        $empTask = Task::orderBy('id', 'desc')
            ->whereIn('id', $taskIds)
            // ->WhereDate('start_date', '>=', $startDate)
            ->get();


        $task_data = [];
        if (count($empTask) > 0) {
            $empTask = $empTask;


            // print_r($empTask);exit();

            foreach ($empTask as $task) {

                $createdByuser = User::find($task['created_by']);

                $createdBy = array(
                    'user_id' => $createdByuser->id,
                    'name' => $createdByuser->name,
                    'email' => $createdByuser->email
                );


                $newData = array(
                    'id'          => $task['id'],
                    'heading'     => $task['heading'],
                    'description' => $task['description'],
                    'start_date'  => $task['start_date'],
                    'due_date'    => $task['due_date'],
                    'priority'    => $task['priority'],
                    'status'      => $task['status'],
                    'created_at'  => $task['created_at'],
                    'updated_at'  => $task['updated_at'],
                    'created_by'  => $createdBy
                );
                array_push($task_data, $newData);
            }
            //$clientInfo['leadCallStatus'] =  $leadStatusText;

        } else {
            $empTask = [];
        }


        $pendingTasks = Task::select('tasks.id', 'tasks.heading', 'tasks.description', 'tasks.start_date', 'tasks.due_date')
            ->join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->where('task_users.user_id', '=', $userId)
            ->get();


        $employeeDashboardData = array(
            'totalLeads'            =>  $totalLeads,
            'availableForCallLeads' =>  $availableForCallLeads,
            'completedLeads'        =>  $completedLeads,
            'followUpLeads'         =>  $followUpLeads,
            'totalProjects'         =>  $totalProjects,

        );

        //contact by source 
        $sourceData = array(
            'others'      =>  $otherLeadCount,
            'email'       =>  $emailLeadCount,
            'google'      =>  $googleLeadCount,
            'facebook'    =>  $facebookLeadCount,
            'friend'      =>  $friendLeadCount,
            'directVisit' =>  $directVisitLeadCount,
            'tvAd'        =>  $tvAdLeadCount
        );
        array_push($data, $employeeDashboardData);
        array_push($contactBySource, $sourceData);
        $ldate = date('Y-m-d');
        $startDate = Carbon::createFromFormat('Y-m-d', $ldate)->startOfDay();
        //event functionality
        // $userEvent = Event::join('event_attendees', 'event_attendees.event_id', '=', 'events.id')
        //     ->where('event_attendees.user_id', $userId)
        //     ->WhereDate('events.start_date_time', '>=', $startDate)
        //     ->select('events.*')
        //     ->orderBy('id', 'desc')
        //     ->limit(4)
        //     ->get();

        $userEvent = Event::where('created_by', $userId)
            ->WhereDate('events.start_date_time', '>=', $startDate)
            ->orderBy('id', 'desc')
            ->limit(4)
            ->get();
        // return $userEvent;
        if (count($userEvent) > 0) {
            $userEvent = $userEvent;
        } else {
            $userEvent = [];
        }

        return response()->json([
            'success'  => true,
            'status'   => 200,
            'code'     => "success",
            'message'  => "Employee dashboard has been fetched successfully",
            'upcomingEvent' => $userEvent,
            'taskList' =>  $task_data,
            'contactedBySource'   =>  $sourceData,
            'dasboardData'     =>  $data,
        ]);
    }

    public function event_list(Request $request)
    {
        $user    = auth('api')->user();
        $userId  = $user->id;

        $ldate = date('Y-m-d');
        if ($request->start_date) {
            $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date)->startOfDay();
        } else {
            $startDate = Carbon::createFromFormat('Y-m-d', $ldate)->startOfDay();
        }

        if (!empty($request->start_date)) {
            $userEvent = Event::WhereDate('start_date_time', '=', $startDate)->get();
        } else {
            $userEvent = Event::where('status', '=', 'pending')->get();
        }


        return response()->json([
            'success'       => true,
            'status'        => 200,
            'code'          => "success",
            'message'       => "Event List has been fetched successfully",
            'upcomingEvent' => $userEvent
        ]);
    }
}
