<?php

namespace App\Http\Controllers\Admin;

use App\Callingdata;
use App\CallPurpose;
use App\Campaign;
use App\CampaignAgent;
use App\CampaignLead;
use App\EmployeeDetails;
use App\Helper\Reply;
use App\Team;
use App\User;
use Illuminate\Http\Request;
use App\CustomFieldGroup;
use App\CallOutcome;
use App\CampaignLeadStatus;
use App\LeadAgent;
use Carbon\Carbon;
use Exception;
use Google\Service\Dfareporting\Resource\Campaigns;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CampaignsImport;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

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
        $this->callOutcomes = CallOutcome::all();
        $this->campaigns = Campaign::all();
        return view('admin.campaign.index', $this->data);
    }

    public function create()
    {
        $camgaignAgents = CampaignAgent::pluck('employee_id');

        $this->employee =  EmployeeDetails::whereNotIn('user_id', $camgaignAgents)->get();


        $this->teams = Team::all();
        return view('admin.campaign.create', $this->data);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|unique:campaigns',
        ]);

        if ($request->type != '') {
            $campType = $request->type;
        } else {
            $campType = 'none';
        }

        if ($request->caller_id != '') {
            $callerId = $request->caller_id;
        } else {
            $callerId = '';
        }

        if ($request->call_to_call_gap != '') {
            $callToGap = $request->call_to_call_gap;
        } else {
            $callToGap = '';
        }

        if ($request->break_time != '') {
            $breakTime = $request->break_time;
        } else {
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

                $camgaignAgents = CampaignAgent::pluck('employee_id');

                if (!empty($team)) {
                    foreach ($team->member->whereNotIn('user_id', $camgaignAgents) as $member) {

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

        $employee1 = CampaignAgent::where('campaign_id', $id)->pluck('employee_id');
        $campaigns = Campaign::pluck('id');
        $employeex = CampaignAgent::whereIn('campaign_id', $campaigns)->pluck('employee_id');
        $employee2 = EmployeeDetails::whereNotIn('user_id', $employeex)->pluck('user_id');
        $employee = $employee1->merge($employee2);
        $this->employee =  EmployeeDetails::whereIn('user_id', $employee)->get();

        $this->teams = Team::all();
        $this->campaign = Campaign::findOrFail($id);

        return view('admin.campaign.edit', $this->data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name' => 'required|unique:campaigns,name,'.$id,
        ]);
        
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

                $employee1 = CampaignAgent::where('campaign_id', $id)->pluck('employee_id');
                $campaigns = Campaign::pluck('id');
                $employeex = CampaignAgent::whereIn('campaign_id', $campaigns)->pluck('employee_id');
                $employee2 = EmployeeDetails::whereNotIn('user_id', $employeex)->pluck('user_id');
                $employee = $employee1->merge($employee2);

                if (!empty($agentGroupId)) {
                    foreach ($team->member->whereIn('user_id', $employee) as $member) {
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


        $this->leadAgents = LeadAgent::with('user')->has('user')->get();
        $this->callPusposes = CallPurpose::where('company_id', '=', company()->id)->get();
        $this->employee = EmployeeDetails::all();
        $this->callOutcomes = CallOutcome::all();
        $this->campaignLeadStatuses = CampaignLeadStatus::all();
        $this->teams = Team::all();
        $this->campaign = Campaign::findOrFail($id);


        if ($request->ajax()) {
            $campaigns = CampaignLead::query()->where('campaign_id', $this->campaign->id);


            if ($request->start_date) {
                $campaigns->WhereDate('created_at', '>=', date('Y-m-d', strtotime($request->start_date)));
            }
            if ($request->end_date) {
                $campaigns->WhereDate('created_at', '<=', date('Y-m-d', strtotime($request->end_date)));
            }
            if ($request->agent_id) {
                $campaigns->where('agent_id', $request->agent_id);
            }

            if ($request->campaignStatus) {
                $campaigns->where('status', '=', ($request->campaignStatus - 1));
            }

            if ($request->callOutcome) {

                $campaigns->where('leadcallstatus', '=', $request->callOutcome);
            }
            $campaigns->with(['lead', 'agent', 'calloutcome']);


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

    public function import(Request $request,Response $response)
    {
       
        $validator = Validator::make($request->all(), [
            'file' => ['required','mimes:xlsx, csv']
          
            
        ]); // create the validations
        if ($validator->fails())   //check all validations are fine, if not then redirect and show error messages
        {
            
            return back()->withInput()->withErrors($validator);
          
        }
        else
        {
            $campaign_id = $request->id;
            
            Excel::import(new CampaignsImport, $request->file);

            return back()->withMessage('file successfully imported');
        }  
        // $request->validate([
        //     'file'=> 'required|mimes:xlsx, csv, xls'
        //  ]);

        
    }

    
}
