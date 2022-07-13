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

        $compain = Campaign::all();
            return response()->json([
            'status' => 'success',
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

        $clientInfo = Lead::whereIn('id', $leadId)->get();
        if(count($clientInfo)>0) {
            $clientInfo = $clientInfo;
        } else {
            $clientInfo = "No lead exist";
        }
            return response()->json([
                'status' => 'success',
                'campaign_info'=> $compain,
                'lead'=> $clientInfo
            ]);
        }
    }