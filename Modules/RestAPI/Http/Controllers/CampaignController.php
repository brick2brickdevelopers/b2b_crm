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
    public function campign_list(Request $request)
    {
        $user      = auth('api')->user();
        $userId    = $user->id;
        $perPage   = $request->page_size;


        $compain = Campaign::join('campaign_leads', 'campaign_leads.campaign_id', '=', 'campaigns.id')
        ->where('campaign_leads.agent_id', $userId)
        ->select('campaigns.*')
        ->groupby('campaigns.id')
        ->orderBy('campaigns.id', 'desc')
        ->paginate($perPage);
        // $compain   = Campaign::orderBy('id', 'desc')->paginate($perPage);
        if(count($compain)>0) {
            $compain = $compain;
        } else {
            $compain = [];
        }
            return response()->json([
                'success'  => true,
                'status'   => 200,
                'code'     => "success",
                'message'  => "Campaign retrieved successfully",
                'campaign' =>  $compain
              //  'count'    => $data 
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
            $callStatus = $request->leadcallstatus;
       // echo $callStatus;exit();

            if($callStatus==='0') {
               // echo "here is 0";
                //exit();
                $callStatus = ['0'];
                $leadStatusText = 'Available';
            }

            if($callStatus==1)  {
                $callStatus = [1];
                $leadStatusText = 'completed';
            }

            if($callStatus==2) {
                $callStatus = [2];
                $leadStatusText = 'folow up';
            }
            if(!$callStatus) {
                $callStatus = [0,1,2];
            }


           //print_r($callStatus);exit();
        $orders = CampaignLead::where(['campaign_id' => $request->campaign_id, 'agent_id' => $userId])
        ->whereIn('leadcallstatus',$callStatus)
        ->get();
        //$orders = CampaignLead::where('agent_id', $request->user_id)->get();
        $leadId = [];
        foreach($orders as $order) {
               array_push($leadId, $order['lead_id']);
        }
        //per page

       
        $perPage = $request->page_size;
    
        $clientInfo = Lead::whereIn('id', $leadId)->paginate($perPage);
        $abc = [];
        if(count($clientInfo)>0) {
            $clientInfo = $clientInfo;


        foreach($clientInfo as $cli) {
            //assign lead call status to the lead


                if($cli['status_id']==4) {
                    $cli['leadCallStatus'] = 'Available';
                }
                if($cli['status_id']==1) {
                    $cli['leadCallStatus'] = 'Completed';
                }
                if($cli['status_id']==2 ) {
                    $cli['leadCallStatus'] = 'follow up';
                }

                
               // array_push($abc,$cli);
        }

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
        public function call_disposal(Request $request) {

            $user           = auth('api')->user();
            $userId         = $user->id;
            $agentNumber    = $user->mobile;
            $campaignId     = $request->campaign_id;
            $leadId         = $request->lead_id;
            $callPurpose    = $request->call_purpose;
            $leadMobile     = $request->lead_mobile;
            // $callStatus     = $request->call_status;
            $callType       = $request->call_type;
            $callSource     = $request->call_source; 
            $leadcallstatus = $request->leadcallstatus;
            $outcome        = $request->outcome;

            if(!$agentNumber) {
                $agentNumber = 12345678;
            } 

            //return the lead name and the lead email based on the lead id
           $leadData  = Lead::where('id', $leadId)->first();
           $leadName  = $leadData->client_name;
           $leadEmail = $leadData->client_email;
           if($request->lead_name) {
                $leadName = $request->lead_name;
           }
           else {
            $leadName = $leadName;
           }
           if($request->lead_email) {
                $leadEmail = $request->lead_email;
            }
             else {
                 $leadEmail = $leadEmail;
            }
            
           //update the lead data based on the lead id 
           Lead::where('id', $leadId)->update(array('client_name' => $leadName, 'client_email'=> $leadEmail));
            // $description    = $request->description;
             //leadcallstatus 
                //0 is Available
                //1 is Completed
                //2 is Follow Up
        
            // call Status is database
            //     1- available,2- completed,3- follow 

            //call Type
             //0=Manual,1=Auto

            //call_source
            //1=Incoming,0=Outgoing

            if($leadcallstatus==='0') {
                $callStatus = 1;
            }
            if($leadcallstatus==1) {
                $callStatus = 2;
            }
            if($leadcallstatus == 2) {
                $callStatus = 3;
            }
            
            //Out come status
            // 1 => 'in Process'; 2 => 'Running'; 3 => 'Both Answered';
            // 4 => 'To (Customer) Answered - From (Agent) Unanswered'; 
            // 5 => 'To (Customer) Answered';
            // 6 => 'To (Customer) Unanswered - From (Agent) Answered.'; 
            // 7 => 'From (Agent) Unanswered';
            // 8 => 'To (Customer) Unanswered.';
            // 9 => 'Both Unanswered'; 
            // 10 => 'From (Agent) Answered.';
            // 11 => 'Rejected Call'; 
            // 12 => 'Skipped';
            // 13 => 'From (Agent) Failed.';
            // 14 => 'To (Customer) Failed - From (Agent) Answered';
            // 15 => 'To (Customer) Failed'; 16 => 'To (Customer) Answered - From (Agent) Failed';
            //need to store the all disposal form data in the database
            //in the table mannual_logged_calls

            $duration = '00:00:00';
           // $recordingsFile
            $manualLoggedCallData = ManualLoggedCall::create([
                'company_id'       => 1,
                'lead_id'          => $leadId,
                'lead_number'      => $leadMobile,
                'agent_number'     => $agentNumber,
                'created_by'       => $userId,
                'outcome'          => $outcome,
                'call_status'      => $callStatus,
                'duration'         => $duration,
                'status'           => $leadcallstatus,
                'call_type'        => $callType,
                'call_source'      => $callSource,
                'call_purpose'     => $callPurpose,
                'campaign_id'      => $campaignId
               
            ]);


            //store the call_purpose and user_id
            $callPurpose  = CallPurpose::create([
                'company_id' => 1,
                'purpose' => $callPurpose,
                'from_id' =>  $userId,
            ]);
               
            CampaignLead::where('lead_id', $leadId)->update(array('leadcallstatus' => $leadcallstatus));
            //update the lead status 
            Lead::where('id', $leadId)->update(array('status_id' => $leadcallstatus));
        //get single lead data
        $leadRecord =  Lead::where('id', $leadId)->first();

            //store the other calling data and other 
            $callingData  = Callingdata::create([
                'lead_id'       =>  $leadId,
                'campaign_id'   =>  $campaignId,
                'mobile'        =>  $leadMobile,
                'agent_id'      =>  $userId,
                'agent_mobile'  =>  $agentNumber,
                'company_id'    =>  1
            ]);

            $data = [];

            if($leadcallstatus==0) {
                $updatedLeadStatus = 'Available';
            }
            elseif($leadcallstatus==1) {
                $updatedLeadStatus = 'Completed';
            }
            else {
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
            array_push($data,$newData);
            return response()->json([
                'success'  => true,
                'status'   => 200,
                'code'     => "success",
                'message'  => "call disposal has been initiated successfully",
                'data'     =>  $data,
                // 'leadData' => $leadRecord
            ]);
        } 
//employee dashboard Api

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


            $userTask = TaskUser::where('user_id', $userId)
            ->limit(4)->get();
            $taskIds = [];
foreach($userTask as $task) {
       array_push($taskIds, $task['task_id']);
}
//per page

$ldate      = date('Y-m-d');
$startDate  = Carbon::createFromFormat('Y-m-d', $ldate)->startOfDay();

    $empTask = Task::orderBy('id', 'desc')
    ->whereIn('id', $taskIds)
    ->WhereDate('start_date','>=',$startDate)
    ->get();


$task_data = [];
if(count($empTask)>0) {
    $empTask = $empTask;


   // print_r($empTask);exit();

foreach($empTask as $task) {

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


        $pendingTasks = Task::select('tasks.id','tasks.heading','tasks.description','tasks.start_date','tasks.due_date')
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
        $userEvent = Event::join('event_attendees', 'event_attendees.event_id', '=', 'events.id')
        ->where('event_attendees.user_id', $userId)
        ->WhereDate('events.start_date_time','>=',$startDate)
        ->select('events.*')
        ->orderBy('id', 'desc')
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

    //call purpose api
    public function call_purpose(Request $request) {
        $perPage = $request->page_size;
        $data = CallPurpose::orderBy('id', 'desc')->paginate($perPage);
        return response()->json([
            'success'     => true,
            'status'      => 200,
            'message'     => "call Purpose data has been fetched successfully",
            'callPurpose' =>  $data,
        ]);   
    }

    public function event_list(Request $request) {
        $user    = auth('api')->user();
        $userId  = $user->id;
        $ldate = date('Y-m-d');
        if($request->start_date) {
            $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date)->startOfDay();
        } else {
            $startDate = Carbon::createFromFormat('Y-m-d', $ldate)->startOfDay();
        }
    
        $userEvent = Event::join('event_attendees', 'event_attendees.event_id', '=', 'events.id')
        ->where('event_attendees.user_id', $userId)
       ->WhereDate('events.start_date_time','=',$startDate)
        ->select('events.*')
        ->orderBy('id', 'desc')
        ->get();
        return response()->json([
            'success'       => true,
            'status'        => 200,
            'code'          => "success",
            'message'       => "Event List has been fetched successfully",
            'upcomingEvent' => $userEvent
        ]);   
    }


//call log Reports Api

        public function call_log_reports(Request $request) {

            $perPage = $request->page_size;
            $user         = auth('api')->user();
            $userId       = $user->id;
            $agentNumber  = $user->mobile;
            if(!$agentNumber) {
                $agentNumber = 12345678;
            }
            $callReportData = ManualLoggedCall::where('agent_number', $agentNumber)->paginate($perPage);


            $datanew = [];
            foreach($callReportData as $data) {
                if($data['call_type']==1) {
                    $data['call_type'] = 'Auto';
                }
                else {
                    $data['call_type'] = 'Mannual';
                }
                if($data['call_source']==1) {
                    $data['call_source'] = 'Incoming';
                }
                else {
                    $data['call_source'] = 'Outgoing';
                }
                if($data['status']==1) {
                    $data['call_status'] = 'Completed';
                }
                elseif($data['status']==2) {
                    $data['call_status'] = 'follow Up';
                }
                else {
                    $data['call_status'] = 'available';
                }

                $data['created_by'] = $user->name;
        //unset some variable
                unset($data['date']);
                unset($data['description']);
                unset($data['status']);
                unset($data['terminate']);
                unset($data['recordings_file']);
                unset($data['did']);
                unset($data['session_id']);
                unset($data['reason_text']);
                unset($data['outcome']);

            }

            return response()->json([
                'success'       => true,
                'status'        => 200,
                'code'          => "success",
                'message'       => "call log report fetched successfully",
                'callReportData' => $callReportData
            ]);
            
        
        }

    }