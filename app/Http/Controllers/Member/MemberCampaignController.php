<?php

namespace App\Http\Controllers\Member;

use App\CallOutcome;
use App\Campaign;
use App\CampaignAgent;
use App\CampaignLead;
use App\EmployeeDetails;
use App\LeadAgent;
use App\CallPurpose;
use App\CampaignLeadStatus;
use App\Helper\Reply;
use App\Http\Controllers\Controller;
use App\Lead;
use App\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class MemberCampaignController extends MemberBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageIcon = 'icon-list';
        $this->pageTitle = 'Campaigns';
    }

    public function index()
    {
        $this->userId = Auth::user()->id;
        $this->callOutcomes = CallOutcome::all();
        $this->agent = CampaignAgent::where('employee_id', auth()->user()->id)->pluck('id');
        $this->campaigns = Campaign::findMany($this->agent);
        return view('member.campaign.index', $this->data);
    }

    public function create()
    {
        $this->employee = EmployeeDetails::all();
        $this->teams = Team::all();
        return view('member.campaign.create', $this->data);
    }

    public function store(Request $request)
    {


        $campaign = new Campaign();
        $campaign->type = $request->type;
        $campaign->name = $request->name;
        $campaign->status = $request->status;
        $campaign->caller_id = $request->caller_id;

        $campaign->start_date = ($request->start_date != '') ? date('Y-m-d', strtotime($request->start_date)) : NULL;
        $campaign->end_date = ($request->end_date != '') ? date('Y-m-d', strtotime($request->end_date)) : NULL;
        $campaign->call_to_call_gap = $request->call_to_call_gap;
        $campaign->break_time = $request->break_time;


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
                if (!empty($agentGroupId)) {
                    foreach ($team->member as $member) {
                        DB::table('campaign_agents')->insert([
                            'campaign_id' => $campaign->id,
                            'agent_id' => $member->id,
                        ]);
                    }
                }
            }
        }
        return Reply::redirect(route('member.campaigns.index'), __('messages.groupUpdatedSuccessfully'));
    }

    public function edit($id)
    {
        $this->employee = EmployeeDetails::all();
        $this->teams = Team::all();
        $this->campaign = Campaign::findOrFail($id);
        return view('member.campaign.edit', $this->data);
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
        $campaign->save();
        return Reply::redirect(route('member.campaigns.index'), __('messages.groupUpdatedSuccessfully'));
    }

    public function view(Builder $builder, Request $request, $id)
    {
        $this->leadAgents = LeadAgent::with('user')->has('user')->get();
        $this->callPusposes = CallPurpose::where('company_id', '=', company()->id)->get();
        $this->callOutcomes = CallOutcome::all();
        $this->campaignLeadStatuses = CampaignLeadStatus::all();
        $this->employee = EmployeeDetails::all();
        $this->teams = Team::all();
        $this->campaign = Campaign::findOrFail($id);

            if ($request->ajax()) {
                $campaigns = CampaignLead::query()->where('campaign_id', $this->campaign->id)->where('agent_id', auth()->user()->id)
                    ->with(['lead', 'agent']);

                    if ($request->start_date) {
                        $campaigns->WhereDate('created_at', '>=', date('Y-m-d', strtotime($request->start_date)));
                    }
                    if ($request->end_date) {
                        $campaigns->WhereDate('created_at', '<=', date('Y-m-d', strtotime($request->end_date)));
                    }
                    if ($request->campaignStatus) {
                        $campaigns->where('status', ($request->campaignStatus - 1));
                    }
    
                    if ($request->callOutcome) {
                        $campaigns->where('leadcallstatus', $request->callOutcome);
                    }
                   
                 
                return  DataTables::of($campaigns)->editColumn('status', function ($row) {
                    if ($row->status == 0) {
                        return 'Avaiable';
                    }
                    if ($row->status == 1) {
                        return 'Completed';
                    }
                    if ($row->status == 2) {
                        return 'Follow';
                    }
                })->editColumn('calloutcome', function ($item) {
                    if ($item->calloutcome) {
                        return $item->calloutcome->name;
                    } else {
                        return "N/A";
                    }
                })
                    ->toJson();
        }

        $this->html = $builder->columns([
            ['data' => 'id', 'name' => 'id', 'title' => 'Id'],
            ['data' => 'lead.client_name', 'name' => 'name', 'title' => 'Lead Name'],
            ['data' => 'agent.name', 'name' => 'name', 'title' => 'AssignedTo'],
            // ['data' => 'leadcallstatus', 'name' => 'name', 'title' => 'Call Status'],
            ['data' => 'lead.mobile', 'name' => 'name', 'title' => 'Lead Mobile'],
            ['data' => 'agent.mobile', 'name' => 'name', 'title' => 'Agent Mobile'],
            ['data' => 'status', 'name' => 'status', 'title' => 'Campaign Status'],
            ['data' => 'calloutcome', 'name' => 'leadcallstatus', 'title' => 'Call Outcome'],
            // ['data' => 'email', 'name' => 'email', 'title' => 'Email'],
            // ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created At'],
            // ['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Updated At'],
        ])->setTableId("tab-table");

        return view('member.campaign.view', $this->data);
    }
}
