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


use App\AttendanceSetting;
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



public function test() {
   
    
}




//all campign list
    public function campign_list(Request $request)
    {
        
        $perPage = $request->page_size;

        $compain = Campaign::paginate($perPage);

        if(count($compain)>0) {
            $$compain = $compain;
        } else {
            $$compain = [];
        }

            return response()->json([
                'success'  => true,
                'status'   => 200,
                'code'     => "success",
                'message'  => "Campaign retrieved successfully",
                'campaign' => $compain,
        ]);
    }


    //list of lead assingned to particular user
    public function user_lead(Request $request) {
        //get the user id from the token
        $user    = auth('api')->user();
        $userId  = $user->id;
        $compain = Campaign::where('id', $request->campaign_id)->get();
                //leadcallstatus 
                //0 is Available
                //1 is Completed
                //2 is Follow Up
            $leadStatusText = '';
            if($request->leadcallstatus) {
                $callStatus = $request->leadcallstatus;
            } else {
                $callStatus = 0;
            }

            if($callStatus == 0) {
                $leadStatusText = 'Available';
            }
            if($callStatus==1)  {
                $leadStatusText = 'completed';
            }

            if($callStatus==2) {
                $leadStatusText = 'folow up';
            }

        $orders = CampaignLead::where(['campaign_id' => $request->campaign_id, 'agent_id' => $userId,'leadcallstatus' => $callStatus])->get();
        //$orders = CampaignLead::where('agent_id', $request->user_id)->get();
        $leadId = [];
        foreach($orders as $order) {
               array_push($leadId, $order['lead_id']);
        }
        //per page

       
        $perPage = $request->page_size;
        $clientInfo = Lead::whereIn('id', $leadId)->paginate($perPage);
        if(count($clientInfo)>0) {
            $clientInfo = $clientInfo;

            //$clientInfo['leadCallStatus'] =  $leadStatusText;
           
        } else {
            $clientInfo = [];
        }

        
            return response()->json([
                'success'  => true,
                'status'   => 200,
                'code'     => "success",
                'message'  => "Lead retrieved successfully",
                'campaign_info'=> $compain,
                'lead'=> $clientInfo
            ]);
        }
        //update the particular lead status based on lead id , user id and compaign_id
        public function update_lead_status(Request $request) {
            $user    = auth('api')->user();
            $userId  = $user->id;

            CampaignLead::where(['campaign_id'=>$request->campaign_id,'agent_id'=>$userId,'lead_id'=>$request->lead_id])
            ->update(['status'=>$request->status]);
            $updatedLead = Lead::where('id', $request->lead_id)->get();
            return response()->json(['success'=>'true','status'=> 200, 'code'=> "success",'message'=>'Lead status has been updated successfully','Updatedlead'=>$updatedLead]);
        }

        //call disposal api
        public function call_disposal(Request $request ) {

            $user    = auth('api')->user();
            $userId  = $user->id;
            //store the call_purpose and user_id
            $callPurpose  = CallPurpose::create([
                'company_id' => 1,
                'purpose' => $request->call_purpose,
                'from_id' =>  $userId,
            ]);
                //leadcallstatus 
                //0 is Available
                //1 is Completed
                //2 is Follow Up
         $callingData  = CampaignLead::create([
                'lead_id' =>  $request->lead_id,
                'campaign_id' => $request->campaign_id,
                'agent_id' => $userId,
                'status' => $request->leadcallstatus,
                'leadcallstatus'=> $request->leadcallstatus,
                'company_id' => 1
            ]);


            //store the other calling data and other 
            $callingData  = Callingdata::create([
                'lead_id' =>  $request->lead_id,
                'campaign_id' => $request->campaign_id,
                'mobile' =>  $request->lead_mobile,
                'agent_id' => $userId,
                'agent_mobile' => $request->agent_mobile,
                'company_id' => 1
            ]);

            $data = [];

            if($request->leadcallstatus==1) {
                $updatedLeadStatus = 'Available';
            }
            elseif($request->leadcallstatus==2) {
                $updatedLeadStatus = 'Completed';
            }
            else {
                $updatedLeadStatus = 'Follow Up';
            }

            $newData = array(
                'lead_id' =>  $request->lead_id,
                'campaign_id' => $request->campaign_id,
                'lead_mobile' =>  $request->lead_mobile,
                'agent_id' => $userId,
                'agent_mobile' => $request->agent_mobile,
                'purpose' => $request->call_purpose,
                'leadcall_status'=> $updatedLeadStatus
            );

            array_push($data,$newData);
            return response()->json([
                'success'  => true,
                'status'   => 200,
                'code'     => "success",
                'message'  => "call disposal has been initiated successfully",
                'data'=>  $data

            ]);
        } 


    public function employee_dashboard(Request $request) {
        $user    = auth('api')->user();
        $userId  = $user->id;
        
        $data = [];
        $contactBySource = [];
        $totalLeads             = CampaignLead::where('agent_id', $userId)->count();
        $availableForCallLeads  = CampaignLead::where(['agent_id' => $userId,'leadcallstatus' => 0])->count();
        $completedLeads         = CampaignLead::where(['agent_id' => $userId,'leadcallstatus' => 1])->count();
        $followUpLeads          = CampaignLead::where(['agent_id' => $userId,'leadcallstatus' => 2])->count();
        // $userTask               = Task::where('user_id', $request->user_id)->count();

        //contact by source
        //1 email
        //2 google
        //3 facebook
        //4 friend
        //5 direct visit
        //6 tv ad
        //38 other

        $otherLeadCount         = Lead::where(['agent_id' => $userId,'source_id' => 38])->count();
        $emailLeadCount         = Lead::where(['agent_id' => $userId,'source_id' => 1])->count();
        $googleLeadCount        = Lead::where(['agent_id' => $userId,'source_id' => 2])->count();
        $facebookLeadCount      = Lead::where(['agent_id' => $userId,'source_id' => 3])->count();
        $friendLeadCount        = Lead::where(['agent_id' => $userId,'source_id' => 4])->count();
        $directVisitLeadCount   = Lead::where(['agent_id' => $userId,'source_id' => 5])->count();
        $tvAdLeadCount          = Lead::where(['agent_id' => $userId,'source_id' => 6])->count();


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


            $userTask = TaskUser::where('user_id', $userId)->limit(4)->get();
            $taskIds = [];
foreach($userTask as $task) {
       array_push($taskIds, $task['task_id']);
}
//per page
$empTask = Task::whereIn('id', $taskIds)->get();
$task_data = [];
if(count($empTask)>0) {
    $empTask = $empTask;

foreach($empTask as $task) {

    $newData = array(
        'id'          => $task['id'],
        'heading'     => $task['heading'],
        'description' => $task['description'],
        'start_date'  => $task['start_date'],
        'due_date'    => $task['due_date'],
        'priority'    => $task['priority'],
        'created_at'  => $task['created_at'],
        'updated_at'  => $task['updated_at'],
    );
    array_push($task_data, $newData);

}


    //$clientInfo['leadCallStatus'] =  $leadStatusText;
   
} else {
    $empTask = [];
}


        $pendingTasks = Task::select('tasks.id','tasks.heading','tasks.description','tasks.start_date','tasks.due_date')
            ->join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->where('task_users.user_id', '=', $userId)
            ->get();

        
        $employeeDashboardData = array(
            'totalLeads'            =>  $totalLeads,
            'availableForCallLeads' => $availableForCallLeads,
            'completedLeads'        =>  $completedLeads,
            'followUpLeads'         => $followUpLeads,
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


//event functionality
        $userEvent = Event::join('event_attendees', 'event_attendees.event_id', '=', 'events.id')
        ->where('event_attendees.user_id', $userId)
        ->select('events.*')
        ->limit(4)
        ->get();


        if(count($userEvent)>0) {
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
  
    }