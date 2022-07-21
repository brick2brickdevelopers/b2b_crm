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

use Froiden\RestAPI\ApiResponse;
use Froiden\RestAPI\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;
use Modules\RestAPI\Entities\Attendance;
use Modules\RestAPI\Entities\Invoice;
use Modules\RestAPI\Entities\Project;
use Modules\RestAPI\Entities\Task;

use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;




class CampaignController extends ApiBaseController
{

    //list of all the campaign
    public function index()
    {
        echo "here";
        
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
        $compain = Campaign::where('id', $request->campaign_id)->get();
                //leadcallstatus 
                //0 is Available
                //1 is Completed
                //2 is Follow Up

        $orders = CampaignLead::where(['campaign_id' => $request->campaign_id, 'agent_id' => $request->user_id,'leadcallstatus' => $request->leadcallstatus])->get();
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
            CampaignLead::where(['campaign_id'=>$request->campaign_id,'agent_id'=>$request->user_id,'lead_id'=>$request->lead_id])
            ->update(['status'=>$request->status]);
            $updatedLead = Lead::where('id', $request->lead_id)->get();
            return response()->json(['success'=>'true','status'=> 200, 'code'=> "success",'message'=>'Lead status has been updated successfully','Updatedlead'=>$updatedLead]);
        }

        //call disposal api
        public function call_disposal(Request $request) {
            //store the call_purpose and user_id
            $callPurpose  = CallPurpose::create([
                'company_id' => 1,
                'purpose' => $request->call_purpose,
                'from_id' =>  $request->user_id,
            ]);
                //leadcallstatus 
                //0 is Available
                //1 is Completed
                //2 is Follow Up
         $callingData  = CampaignLead::create([
                'lead_id' =>  $request->lead_id,
                'campaign_id' => $request->campaign_id,
                'agent_id' => $request->user_id,
                'status' => $request->leadcallstatus,
                'leadcallstatus'=> $request->leadcallstatus,
                'company_id' => 1
            ]);


            //store the other calling data and other 
            $callingData  = Callingdata::create([
                'lead_id' =>  $request->lead_id,
                'campaign_id' => $request->campaign_id,
                'mobile' =>  $request->lead_mobile,
                'agent_id' => $request->user_id,
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
                'agent_id' => $request->user_id,
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


        //dashboard Api detail
    public function dashboard() {

        $data = [];
        //client count 
        $totalClient    =  ClientDetails::count();
        //employee Count
        $totalEmployee  =  EmployeeDetails::count();
        //total campaign 
        $totalCampagin  = Campaign::count();   
        //total Leads
        $totalLeads     = Lead::count();

        $taskBoardColumn = TaskboardColumn::all();

        $completedTaskColumn = $taskBoardColumn->filter(function ($value, $key) {
            return $value->slug == 'completed';
        })->first();

        $totalProjects = Project::select('projects.id')
            ->join('project_members', 'project_members.project_id', '=', 'projects.id')
            ->where('project_members.user_id', '=', api_user()->id)
            ->groupBy('projects.id')
            ->get()
            ->count();

        $pendingTasks = Task::select('tasks.id')
            ->where('board_column_id', '!=', $completedTaskColumn->id)
            ->join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->where('task_users.user_id', '=', api_user()->id)
            ->groupBy('tasks.id')
            ->get()
            ->count();


        $unpaidInvoices = Invoice::select('invoices.id')
            ->where('status', "unpaid")
            ->get()
            ->count();


        $dashboardData = array(
            'totalClient'    =>  $totalClient,
            'totalEmployee'  =>  $totalEmployee,
            'totalCampaign'  =>  $totalCampagin,
            'unpaidInvoices' =>  $unpaidInvoices,
            'totalLeads'     =>  $totalLeads,
            'totalProjects'  =>  $totalProjects,
            'pendingTasks'   =>  $pendingTasks
        );
        array_push($data,$dashboardData);
        return response()->json([
            'success'  => true,
            'status'   => 200,
            'code'     => "success",
            'message'  => "dashboard Api has been fetched successfully",
            'data'=>  $data
        ]);   
    }
    }