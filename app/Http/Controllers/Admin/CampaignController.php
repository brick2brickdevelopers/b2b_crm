<?php

namespace App\Http\Controllers\Admin;

use App\Callingdata;
use App\CallPurpose;
use App\Campaign;
use App\CampaignAgent;
use App\CampaignLead;
use App\EmployeeDetails;
use App\EmployeeTeam;
use App\Helper\Reply;
use App\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\CustomField;
use App\CustomFieldGroup;
use Exception;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class CampaignController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageIcon = 'icon-list';
        $this->pageTitle = 'Campaigns';
        // $this->middleware(function ($request, $next) {
        //     if (!in_array('campaigns', $this->user->modules)) {
        //         abort(403);
        //     }
        //     return $next($request);
        // });
    }

    public function index()
    {
        $this->campaigns = Campaign::all();
        return view('admin.campaign.index', $this->data);
    }

    public function create()
    {
        $this->employee = EmployeeDetails::all();
        $this->teams = Team::all();
        return view('admin.campaign.create', $this->data);
    }

    public function store(Request $request)
    {
        
        if($request->type !='') {
            $campType = $request->type;
        }
        else {
            $campType = 'none';
        }

        if($request->caller_id !='') {
            $callerId = $request->caller_id;
        }
        else {
            $callerId = '';
        }

        if($request->call_to_call_gap !='') {
            $callToGap = $request->call_to_call_gap;
        }
        else {
            $callToGap = '';
        }

        if($request->break_time !='') {
            $breakTime = $request->break_time;
        }
        else {
            $breakTime = '';
        }
       // return ($request->all());
        $campaign = new Campaign();
        $campaign->type = $campType;
        $campaign->name = $request->name;
        $campaign->status = $request->status;
        $campaign->caller_id = $callerId;
        $campaign->start_date = ($request->start_date != '') ? date('Y-m-d', strtotime($request->start_date)) : NULL;
        $campaign->end_date = ($request->end_date != '') ? date('Y-m-d', strtotime($request->end_date)) : NULL;
        $campaign->call_to_call_gap = $callToGap;
        $campaign->break_time = $breakTime;


        if ($campaign->save()) {
           
            if ($request->agent != null) {
               
                foreach ($request->agent as $ag) {
                    CampaignAgent::create([
                        'campaign_id' => $campaign->id,
                        'employee_id' => $ag,

                    ]);
                }
            }
            if ($request->agentGroup != null) {
               
                $team = Team::find($request->agentGroup);

                if (!empty($team)) {
                    foreach ($team->member as $member) {

                        try {
                            CampaignAgent::create([
                                'campaign_id' => $campaign->id,
                                'employee_id' => $member->user_id,

                            ]);
                        } catch (Exception $e) {
                        }
                    }
                }
            }
        }
        return Reply::redirect(route('admin.campaigns.index'), __('messages.groupUpdatedSuccessfully'));
    }

    public function edit($id)
    {
        $this->employee = EmployeeDetails::all();
        $this->teams = Team::all();
        $this->campaign = Campaign::findOrFail($id);
        return view('admin.campaign.edit', $this->data);
    }

    public function update(Request $request, $id)
    {
        $campaign = Campaign::find($id);
        $campaign->type = $request->type;
        $campaign->name = $request->name;
        $campaign->status = $request->status;
        $campaign->caller_id = $request->caller_id;
        $campaign->start_date = ($request->start_date != '') ? date('Y-m-d', strtotime($request->start_date)) : NULL;
        $campaign->end_date = ($request->end_date != '') ? date('Y-m-d', strtotime($request->end_date)) : NULL;
        $campaign->call_to_call_gap = $request->call_to_call_gap;
        $campaign->break_time = $request->break_time;
        if ($campaign->save()) {
            CampaignAgent::where('campaign_id', $campaign->id)->delete();
            if ($request->agent != null) {
                foreach ($request->agent as $ag) {
                    CampaignAgent::create([
                        'campaign_id' => $campaign->id,
                        'employee_id' => $ag,

                    ]);
                }
            }
            if ($request->agentGroup != null) {
                $team = Team::find($request->agentGroup);
                if (!empty($agentGroupId)) {
                    foreach ($team->member as $member) {
                        DB::table('campaign_agents')->insert([
                            'campaign_id' => $campaign->id,
                            'agent_id' => $member->user_id,
                        ]);
                    }
                }
            }
        }
        return Reply::redirect(route('admin.campaigns.index'), __('messages.groupUpdatedSuccessfully'));
    }

    public function view(Builder $builder, Request $request, $id)
    {

        $this->employee = EmployeeDetails::all();
        $this->teams = Team::all();
        $this->campaign = Campaign::findOrFail($id);

        if ($request->ajax()) {
            return DataTables::of(CampaignLead::query()->where('campaign_id', $this->campaign->id)->orderBy(
                'id',
                'DESC'
            )->with(['lead', 'agent']))->toJson();
        }
        $this->html = $builder->columns([
            ['data' => 'id', 'name' => 'id', 'title' => 'Id'],
            ['data' => 'lead.client_name', 'name' => 'name', 'title' => 'Lead Name'],
            ['data' => 'agent.name', 'name' => 'name', 'title' => 'AssignedTo'],
            ['data' => 'leadcallstatus', 'name' => 'name', 'title' => 'Call Status'],
            ['data' => 'lead.mobile', 'name' => 'name', 'title' => 'Lead Mobile'],
            ['data' => 'agent.mobile', 'name' => 'name', 'title' => 'Agent Mobile'],
            ['data' => 'status', 'name' => 'name', 'title' => 'Campaign Status'],
            ['data' => 'agent.mobile', 'name' => 'name', 'title' => 'Call Purpose'],
            // ['data' => 'email', 'name' => 'email', 'title' => 'Email'],
            // ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created At'],
            // ['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Updated At'],
        ]);

        return view('admin.campaign.view', $this->data);
    }

    public function callPurpose(Request $request)
    {

     
        $this->callPurpose = CallPurpose::all();
       
        if ($request->method() == 'POST') {
            $request->validate([
                'purpose' => 'required|unique:call_purposes,purpose,' . company()->id
            ]);
            
            $call_purposes = CallPurpose::findOrNew($request->id);
            $call_purposes->purpose = $request->purpose;
            if ($call_purposes->save()) {
                $custom_field = CustomFieldGroup::findOrNew($request->custom_id);
                $custom_field->name = $request->purpose;
                $custom_field->company_id = company()->id;
                $custom_field->model = 'App\ManualLoggedCall';
                $custom_field->save();
                $call_purposes->from_id = $custom_field->id;
                $call_purposes->save();
            }
            return Reply::redirect(route('admin.campaigns.call-purpose'), __('messages.groupUpdatedSuccessfully'));
        } else {
            return view('admin.campaign.call-purpose', $this->data);
        }
    }

    public function destroy($id)
    {
        $campaign = Campaign::findOrFail($id);
        $check = CampaignLead::where('campaign_id', $campaign->id)->delete();
        $callingCheck = Callingdata::where('campaign_id', $campaign->id)->delete();
        $campaign_agent = CampaignAgent::where('campaign_id', $campaign->id)->delete();
        $campaign->delete();
        return redirect()->route('admin.campaigns.index')->with('success', 'Campaign Deleted Successfully');
    }

    public function destroyCallPurpose($id)
    {
        $callPurpose = CallPurpose::find($id);
        $callPurpose->delete();
        return redirect()->route('admin.campaigns.call-purpose')->with('messages', 'Call Purpose Deleted Successfully');
    }
}
