<?php

namespace Modules\RestAPI\Http\Controllers;
use App\Callingdata;
use App\CallPurpose;
use App\Campaign;
use App\CampaignAgent;
use App\CampaignLead;
use App\EmployeeDetails;
use App\EmployeeTeam;
use App\Helper\Reply;
use App\Team;
use App\Lead;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\CustomField;
use App\CustomFieldGroup;
use Exception;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class CampaignController extends ApiBaseController
{

    //list of all the campaign
    public function index()
    {

        $perPage = 5;
        $compain = Campaign::paginate($perPage);
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
        $orders = CampaignLead::where('campaign_id', $request->campaign_id)->get();
        $orders = CampaignLead::where('agent_id', $request->user_id)->get();
        $leadId = [];
        foreach($orders as $order) {
               array_push($leadId, $order['lead_id']);
        }
        //per page
        $perPage = 5;
        $clientInfo = Lead::whereIn('id', $leadId)->paginate($perPage);
        if(count($clientInfo)>0) {
            $clientInfo = $clientInfo;
        } else {
            $clientInfo = "No lead exist";
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
//1 is Available
//2 is Completed
//3 is Follow Up

            

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
    }