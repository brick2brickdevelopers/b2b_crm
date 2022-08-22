<?php

namespace App\Http\Controllers\Admin;

use App\Callingdata;
use App\ManualLoggedCall;
use App\CallPurpose;
use App\Campaign;
use App\CampaignAgent;
use App\CampaignLead;
use App\DataTables\Admin\LeadsDataTable;
use App\DataTables\Admin\LeadDashboardDataTable;
use App\Helper\Reply;
use App\Http\Requests\CommonRequest;
use App\Http\Requests\FollowUp\UpdateFollowUpRequest;
use App\Http\Requests\Gdpr\SaveConsentLeadDataRequest;
use App\Http\Requests\Lead\StoreRequest;
use App\Http\Requests\Lead\CsvImportRequest;
use App\Http\Requests\Lead\UpdateRequest;
use App\User;
use App\CsvData;
use App\Event;
use App\Lead;
use App\EventCategory;
use App\EventType;
use App\Country;
use App\EmployeeDetails;
use App\LeadAgent;
use App\LeadFollowUp;
use App\LeadSource;
use App\LeadStatus;
use App\LeadCategory;
use App\PurposeConsent;
use App\PurposeConsentLead;
use App\Services\Google;
use App\TaskUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

use App\Exports\LeadsExport;
use App\Imports\LeadsImport;
use App\Jobs\AdminLeadImportJob;
use Maatwebsite\Excel\Facades\Excel;


class LeadController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageIcon = __('icon-people');
        $this->pageTitle = 'app.lead';
        $this->middleware(function ($request, $next) {
            abort_if(!in_array('leads', $this->user->modules), 403);
            return $next($request);
        });
    }

    public function index(LeadsDataTable $dataTable)
    {
        $this->totalLeads = Lead::all();
        $this->sources = LeadSource::all();
        $this->categories = LeadCategory::all();
        $this->totalClientConverted = $this->totalLeads->filter(function ($value, $key) {
            return $value->client_id != null;
        });
        $this->totalLeads = Lead::all()->count();
        $this->totalClientConverted = $this->totalClientConverted->count();

        $this->pendingLeadFollowUps = LeadFollowUp::where(\DB::raw('DATE(next_follow_up_date)'), '<=', Carbon::today()->format('Y-m-d'))
            ->join('leads', 'leads.id', 'lead_follow_up.lead_id')
            ->where('leads.next_follow_up', 'yes')
            ->where('leads.company_id', company()->id)
            ->get();

        $this->pendingLeadFollowUps = $this->pendingLeadFollowUps->count();
        $this->leadAgents = LeadAgent::with('user')->has('user')->get();
        $this->campaigns = Campaign::all();
        return $dataTable->render('admin.lead.index', $this->data);
    }

    public function show($id)
    {
        $this->lead = Lead::findOrFail($id)->withCustomFields();
        $this->fields = $this->lead->getCustomFieldGroupsWithFields()->fields;
        $this->categories = LeadCategory::all();
        return view('admin.lead.show', $this->data);
    }

    public function assign_lead_campaign(Request $request)
    {
        $campaign = Campaign::find($request->campaigns);

        if ($request->leads[0] === 'selectAllLeads') {
            $leads =  Lead::all();
        } else {
            $leads =  Lead::findMany($request->leads);
        }

        foreach ($leads as $lead) {

            $campaign_agent = CampaignAgent::where('campaign_id', $campaign->id)->inRandomOrder()->first();
            if (!$campaign_agent->cAgent) {
                return Reply::redirect(route('admin.leads.index'), 'Leads Agent Not Configured');
            }
            $lx = Lead::find($lead->id);
            $lx->agent_id = $campaign_agent->cAgent->id;

            $lx->save();
            $check = CampaignLead::where('campaign_id', $campaign->id)->where('lead_id', $lead->id)->exists();
            if (!$check) {
                $camLead = new CampaignLead();
                $camLead->campaign_id = $campaign->id;
                $camLead->agent_id = $campaign_agent->agent->id;
                $camLead->lead_id = $lead->id;
                $camLead->status = 0;
                $camLead->leadcallstatus = 0;
                $camLead->save();
            }

            if (in_array('calling', $this->modules)) {
                $callingCheck = Callingdata::where('campaign_id', $campaign->id)->where('lead_id', $lead->id)->exists();
                if (!$callingCheck) {
                    $callLead = new Callingdata();
                    $callLead->lead_id = $lead->id;
                    $callLead->campaign_id = $campaign->id;
                    $callLead->mobile =  "+" . preg_replace('/(?<=\d)\s+(?=\d)/', '', $lead->mobile);
                    $callLead->agent_id =  $campaign_agent->agent->id;
                    $callLead->agent_mobile =  "+" . preg_replace('/(?<=\d)\s+(?=\d)/', '', $campaign_agent->agent->mobile);
                    $callLead->callerid = 0;
                    $callLead->save();
                }
            }
        }


        // CampaignLead::all();

        return Reply::redirect(route('admin.leads.index'), __('messages.LeadUpdated'));
    }

    /*
     *
     */
    public function create()
    {
        $this->leadAgents = LeadAgent::with('user')->get();
        $this->sources = LeadSource::all();
        $this->status = LeadStatus::all();
        $this->categories = LeadCategory::all();
        $this->countries = Country::all();
        $lead = new Lead();
        $this->fields = $lead->getCustomFieldGroupsWithFields()->fields;

        return view('admin.lead.create', $this->data);
    }

    /*
     *
     */
    public function store(StoreRequest $request)
    {
        $leadStatus = LeadStatus::where('default', '1')->first();

        $lead = new Lead();
        $lead->company_name = $request->company_name;
        $lead->website = $request->website;
        $lead->address = $request->address;
        $lead->client_surname = $request->salutation;
        $lead->client_name = $request->name;
        // $lead->client_name = $request->salutation . ' ' . $request->name;
        $lead->client_email = $request->email;
        $lead->mobile = $request->input('phone_code') . ' ' . $request->input('mobile');
        $lead->office_phone = $request->office_phone;
        $lead->city = $request->city;
        $lead->state = $request->state;
        $lead->country = $request->country;
        $lead->postal_code = $request->postal_code;
        $lead->note = $request->note;
        $lead->category_id = $request->category_id;
        $lead->next_follow_up = $request->next_follow_up;
        $lead->agent_id = $request->agent_id;
        $lead->source_id = $request->source_id;
        $lead->value = ($request->value) ? $request->value : 0;
        $lead->currency_id = company()->currency_id;
        $lead->status_id = $leadStatus->id;
        $lead->save();

        // To add custom fields data
        if ($request->get('custom_fields_data')) {
            $lead->updateCustomFieldData($request->get('custom_fields_data'));
        }

        // Log search
        $this->LogEntry($lead);

        return Reply::redirect(route('admin.leads.index'), __('messages.LeadAddedUpdated'));
    }

    private function LogEntry($lead)
    {
        $this->logSearchEntry($lead->id, $lead->client_name, 'admin.leads.show', 'lead');
        if (!is_null($lead->client_email)) {
            $this->logSearchEntry($lead->id, $lead->client_email, 'admin.leads.show', 'lead');
        }

        if (!is_null($lead->company_name)) {
            $this->logSearchEntry($lead->id, $lead->company_name, 'admin.leads.show', 'lead');
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $this->leadAgents = LeadAgent::with('user')->get();
        $this->lead = Lead::findOrFail($id)->withCustomFields();
        $this->fields = $this->lead->getCustomFieldGroupsWithFields()->fields;
        $this->sources = LeadSource::all();
        $this->status = LeadStatus::all();
        $this->categories = LeadCategory::all();
        $this->countries = Country::all();
        return view('admin.lead.edit', $this->data);
    }

    /**
     * @param UpdateRequest $request
     * @param $id
     * @return array|string[]
     */
    public function update(UpdateRequest $request, $id)
    {
        $lead = Lead::findOrFail($id);
        $lead->company_name = $request->company_name;
        $lead->website = $request->website;
        $lead->address = $request->address;
        $lead->client_surname = $request->salutation;
        $lead->client_name = $request->client_name;
        $lead->client_email = $request->email;
        $lead->mobile = $request->input('phone_code') . ' ' . $request->input('mobile');
        $lead->office_phone = $request->office_phone;
        $lead->city = $request->city;
        $lead->state = $request->state;
        $lead->country = $request->country;
        $lead->postal_code = $request->postal_code;

        $lead->note = $request->note;
        $lead->status_id = $request->status;
        $lead->source_id = $request->source;
        $lead->category_id = $request->category_id;
        $lead->next_follow_up = $request->next_follow_up;
        $lead->agent_id = $request->agent_id;
        $lead->value = ($request->value) ? $request->value : 0;
        $lead->currency_id = company()->currency_id;
        $lead->save();

        // To add custom fields data
        if ($request->get('custom_fields_data')) {
            $lead->updateCustomFieldData($request->get('custom_fields_data'));
        }
        //update redirection after update the data
        return Reply::redirect(route('admin.leads.index'), __('messages.LeadUpdated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Lead::destroy($id);
        $this->totalLeads = Lead::all();
        $this->totalClientConverted = $this->totalLeads->filter(function ($value, $key) {
            return $value->client_id != null;
        });
        $this->totalLeadsCount = $this->totalLeads->count();
        $this->totalClientConverted = $this->totalClientConverted->count();

        $this->pendingLeadFollowUps = LeadFollowUp::where(\DB::raw('DATE(next_follow_up_date)'), '<=', Carbon::today()->format('Y-m-d'))
            ->join('leads', 'leads.id', 'lead_follow_up.lead_id')
            ->where('leads.next_follow_up', 'yes')
            ->where('leads.company_id', company()->id)
            ->get()->count();;
        $leadData = [
            'totalLeadsCount' => $this->totalLeadsCount,
            'totalClientConverted' => $this->totalClientConverted,
            'pendingLeadFollowUps' => $this->pendingLeadFollowUps,
        ];

        return Reply::successWithData(__('messages.LeadDeleted'), ['data' => $leadData]);
    }

    /**
     * @param $id
     * @return array
     */
    public function deleteFollow($id)
    {
        LeadFollowUp::destroy($id);
        return Reply::success(__('messages.followUp.deletedSuccess'));
    }

    /**
     * @param CommonRequest $request
     * @return array
     */
    public function changeStatus(CommonRequest $request)
    {
        $lead = Lead::findOrFail($request->leadID);
        $lead->status_id = $request->statusID;
        $lead->save();

        return Reply::success(__('messages.leadStatusChangeSuccess'));
    }


    public function gdpr($leadID)
    {
        $this->lead = Lead::findOrFail($leadID);
        $this->allConsents = PurposeConsent::with(['lead' => function ($query) use ($leadID) {
            $query->where('lead_id', $leadID)
                ->orderBy('created_at', 'desc');
        }])->get();

        return view('admin.lead.gdpr.show', $this->data);
    }

    public function consentPurposeData($id)
    {
        $purpose = PurposeConsentLead::select('purpose_consent.name', 'purpose_consent_leads.created_at', 'purpose_consent_leads.status', 'purpose_consent_leads.ip', 'users.name as username', 'purpose_consent_leads.additional_description')
            ->join('purpose_consent', 'purpose_consent.id', '=', 'purpose_consent_leads.purpose_consent_id')
            ->leftJoin('users', 'purpose_consent_leads.updated_by_id', '=', 'users.id')
            ->where('purpose_consent_leads.lead_id', $id);

        return DataTables::of($purpose)
            ->editColumn('status', function ($row) {
                if ($row->status == 'agree') {
                    $status = __('modules.gdpr.optIn');
                } else if ($row->status == 'disagree') {
                    $status = __('modules.gdpr.optOut');
                } else {
                    $status = '';
                }

                return $status;
            })
            ->make(true);
    }

    public function saveConsentLeadData(SaveConsentLeadDataRequest $request, $id)
    {
        $lead = Lead::findOrFail($id);
        $consent = PurposeConsent::findOrFail($request->consent_id);

        if ($request->consent_description && $request->consent_description != '') {
            $consent->description = $request->consent_description;
            $consent->save();
        }

        // Saving Consent Data
        $newConsentLead = new PurposeConsentLead();
        $newConsentLead->lead_id = $lead->id;
        $newConsentLead->purpose_consent_id = $consent->id;
        $newConsentLead->status = trim($request->status);
        $newConsentLead->ip = $request->ip();
        $newConsentLead->updated_by_id = $this->user->id;
        $newConsentLead->additional_description = $request->additional_description;
        $newConsentLead->save();

        $url = route('admin.leads.gdpr', $lead->id);

        return Reply::redirect($url);
    }

    /**
     * @param $leadID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function followUpCreate($leadID)
    {

        $this->leadID = $leadID;
        $followUp = new LeadFollowUp();
        $this->fields = $followUp->getCustomFieldGroupsWithFields()->fields;
        return view('admin.lead.follow_up', $this->data);
    }

    /**
     * @param CommonRequest $request
     * @return array
     */
    // public function followUpStore(\App\Http\Requests\FollowUp\StoreRequest $request)
    public function followUpStore(Request $request)
    {

        $followUp = new LeadFollowUp();
        $followUp->lead_id = $request->lead_id;
        if ($request->next_follow_up_date) {
            if ($request->has('type')) {
                $followUp->next_follow_up_date = Carbon::createFromFormat('d/m/Y H:i', $request->next_follow_up_date)->format('Y-m-d H:i:s');
            } else {
                $followUp->next_follow_up_date = Carbon::createFromFormat($this->global->date_format, $request->next_follow_up_date)->format('Y-m-d');
            }
        } else {
            $request->next_follow_up_date = Carbon::now();
        }


        $followUp->remark = $request->remark;
        $followUp->save();
        if ($request->get('custom_fields_data')) {
            $followUp->updateCustomFieldData($request->get('custom_fields_data'));
        }

        $this->lead = Lead::findOrFail($request->lead_id);

        $view = view('admin.lead.followup.task-list-ajax', $this->data)->render();

        return Reply::successWithData(__('messages.leadFollowUpAddedSuccess'), ['html' => $view]);
    }

    public function followUpShow($leadID)
    {
        $this->leadID = $leadID;
        $this->lead = Lead::findOrFail($leadID);
        $followUp = new LeadFollowUp();
        $this->fields = $followUp->getCustomFieldGroupsWithFields()->fields;

        return view('admin.lead.followup.show', $this->data);
    }

    public function editFollow($id)
    {
        $this->follow = LeadFollowUp::findOrFail($id);
        $this->fields = $this->follow->getCustomFieldGroupsWithFields()->fields;
        $view = view('admin.lead.followup.edit', $this->data)->render();
        return Reply::dataOnly(['html' => $view]);
    }

    public function UpdateFollow(UpdateFollowUpRequest $request)
    {
        $followUp = LeadFollowUp::findOrFail($request->id);
        $followUp->lead_id = $request->lead_id;

        if ($request->next_follow_up_date) {
            if ($request->has('type')) {
                $followUp->next_follow_up_date = Carbon::createFromFormat('d/m/Y H:i', $request->next_follow_up_date)->format('Y-m-d H:i:s');
            } else {
                $followUp->next_follow_up_date = Carbon::createFromFormat($this->global->date_format, $request->next_follow_up_date)->format('Y-m-d');
            }
        } else {
            $request->next_follow_up_date = Carbon::now();
        }
        if ($request->get('custom_fields_data')) {
            $followUp->updateCustomFieldData($request->get('custom_fields_data'));
        }

        $followUp->remark = $request->remark;
        $followUp->save();

        $this->lead = Lead::findOrFail($request->lead_id);

        $view = view('admin.lead.followup.task-list-ajax', $this->data)->render();

        return Reply::successWithData(__('messages.leadFollowUpUpdatedSuccess'), ['html' => $view]);
    }

    public function followUpSort(CommonRequest $request)
    {
        $leadId = $request->leadId;
        $this->sortBy = $request->sortBy;

        $this->lead = Lead::findOrFail($leadId);
        if ($request->sortBy == 'next_follow_up_date') {
            $order = 'asc';
        } else {
            $order = 'desc';
        }

        $follow = LeadFollowUp::where('lead_id', $leadId)->orderBy($request->sortBy, $order);


        $this->lead->follow = $follow->get();

        $view = view('admin.lead.followup.task-list-ajax', $this->data)->render();

        return Reply::successWithData(__('messages.followUpFilter'), ['html' => $view]);
    }

    public function kanbanboard(Request $request)
    {
        $this->startDate = $startDate = Carbon::now()->subDays(15)->format($this->global->date_format);
        $this->endDate = $endDate = Carbon::now()->addDays(15)->format($this->global->date_format);
        $this->leadAgents = LeadAgent::with('user')->get();
        if (request()->ajax()) {

            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
            $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();

            $boardColumns = LeadStatus::with(['leads' => function ($q) use ($startDate, $endDate, $request) {
                $q->with(['lead_agent', 'lead_agent.user'])
                    ->select('leads.*', \DB::raw("(select next_follow_up_date from lead_follow_up where lead_id = leads.id and leads.next_follow_up  = 'yes' ORDER BY next_follow_up_date asc limit 1) as next_follow_up_date"))
                    ->groupBy('leads.id');

                $q->where(function ($task) use ($startDate, $endDate) {
                    $task->whereBetween(DB::raw('DATE(leads.`created_at`)'), [$startDate, $endDate]);

                    $task->orWhereBetween(DB::raw('DATE(leads.`created_at`)'), [$startDate, $endDate]);
                });


                if ($request->assignedTo != '' && $request->assignedTo != null && $request->assignedTo != 'all') {
                    $q->where('leads.agent_id', '=', $request->assignedTo);
                }
            }])->orderBy('priority', 'asc')->get();

            $this->boardColumns = $boardColumns;

            $this->startDate = $startDate;
            $this->endDate = $endDate;
            $this->assignedTo = $request->assignedTo;

            $view = view('admin.lead.board_data', $this->data)->render();
            return Reply::dataOnly(['view' => $view]);
        }
        return view('admin.lead.kanban_board', $this->data);
    }

    public function updateIndex(Request $request)
    {

        $taskIds = $request->taskIds;
        $boardColumnIds = $request->boardColumnIds;
        $priorities = $request->prioritys;

        $board = LeadStatus::findOrFail($boardColumnIds[0]);
        $valueData = [];
        if (isset($taskIds) && count($taskIds) > 0) {

            $taskIds = (array_filter($taskIds, function ($value) {
                return $value !== null;
            }));

            foreach ($taskIds as $key => $taskId) {
                if (!is_null($taskId)) {
                    $task = Lead::findOrFail($taskId);
                    $task->update(
                        [
                            'status_id' => $boardColumnIds[$key],
                            'column_priority' => $priorities[$key]
                        ]
                    );
                }
            }

            if ($request->draggingTaskId == 0 && $request->draggedTaskId != 0) {
                // $this->logTaskActivity($request->draggedTaskId, $this->user->id, "statusActivity", $board->id);
                // $updatedTask = Task::findOrFail($request->draggedTaskId);
                // event(new TaskUpdated($updatedTask));
            }
            $startDate = $request->startDate;
            $endDate = $request->endDate;

            $boardColumns = LeadStatus::with(['leads' => function ($q) use ($startDate, $endDate, $request) {
                $q->with(['lead_agent', 'lead_agent.user', 'currency'])
                    ->select('leads.*', \DB::raw("(select next_follow_up_date from lead_follow_up where lead_id = leads.id and leads.next_follow_up  = 'yes' ORDER BY next_follow_up_date asc limit 1) as next_follow_up_date"))
                    ->groupBy('leads.id');

                $q->where(function ($task) use ($startDate, $endDate) {
                    $task->whereBetween(DB::raw('DATE(leads.`created_at`)'), ["$startDate", "$endDate"]);

                    $task->orWhereBetween(DB::raw('DATE(leads.`created_at`)'), ["$startDate", "$endDate"]);
                });

                if ($request->assignedTo != '' && $request->assignedTo != null && $request->assignedTo != 'all') {
                    $q->where('leads.agent_id', '=', $request->assignedTo);
                }
            }])->orderBy('priority', 'asc')->get();

            foreach ($boardColumns as $columnData) {
                $valData = ($columnData->leads) ? $columnData->leads->sum('value') : 0;
                $valueData[] = ['columnId' => $columnData->id, 'value' => $valData];
            }
        }



        return Reply::dataOnly(['status' => 'success', 'columnData' => $valueData]);
    }



    //Leads Dasboard
    public function dashboard(Builder $builder, Request $request)
    {

        $this->unique_id = uniqid();
        $this->employees = User::allEmployees();
        $this->events = Event::all();
        $this->clients = User::allClients();
        $this->categories = EventCategory::all();

        $this->eventTypes = EventType::all();
        $this->leads = Lead::all();
        $this->campaigns = Campaign::get();
        $this->agents = EmployeeDetails::get();
        $this->callPurposes = CallPurpose::all();
        if (request()->ajax()) {

            $callPurposes = CallPurpose::all('id');
            $counter = array();
            foreach ($callPurposes as $callPurpose) {
                $logsCount = ManualLoggedCall::where('call_purpose', $callPurpose->id)->count();

                array_push($counter, array("id" => "#call_$callPurpose->id", 'count' => $logsCount));
            }
            array_push($counter, array("id" => "#call_null", 'count' => ManualLoggedCall::whereNull('call_purpose')->count()));
            $leads = CampaignLead::query()->where('status', $request->type);
            if ($request->check_action) {
                $leads->where('campaign_id', $request->check_action);
            }
            if ($request->assign_to_campaign) {
                $leads->where('agent_id', $request->assign_to_campaign);
            }
            $leads->with('lead');

            $dTable = DataTables::of($leads)
                ->editColumn('action', function ($lead) {
                    return view('admin.lead.action', compact('lead'));
                })
                ->toJson();

            // Counting Start
            $totalAvailable = CampaignLead::query();
            if ($request->check_action) {
                $totalAvailable->where('campaign_id', $request->check_action);
            }
            if ($request->assign_to_campaign) {
                $totalAvailable->where('agent_id', $request->assign_to_campaign);
            }
            $totalCompleted = CampaignLead::query();
            if ($request->check_action) {
                $totalCompleted->where('campaign_id', $request->check_action);
            }
            if ($request->assign_to_campaign) {
                $totalCompleted->where('agent_id', $request->assign_to_campaign);
            }
            $totalFollow = CampaignLead::query();
            if ($request->check_action) {
                $totalFollow->where('campaign_id', $request->check_action);
            }
            if ($request->assign_to_campaign) {
                $totalFollow->where('agent_id', $request->assign_to_campaign);
            }

            $this->totalAvailable = $totalAvailable->where('status', 0)->count();
            $this->totalCompleted = $totalCompleted->where('status', 1)->count();
            $this->totalFollow = $totalFollow->where('status', 2)->count();

            // Counting End
            // call details start

            $this->totalCalls = ManualLoggedCall::count();
            $this->totalIncomming = ManualLoggedCall::where('call_source', 1)->count();
            $this->totalOutgoing = ManualLoggedCall::where('call_source', 0)->count();
            $this->totalBoth = 0;
            $this->totalAgent = 0;
            $this->totalCustUnAns = 0;
            $this->totalCustAns = 0;

            // call details end

            return response()->json([
                'data' => $dTable,
                'additional' => $counter,
                'totalCalls' => $this->totalCalls,
                'totalIncomming' => $this->totalIncomming,
                'totalOutgoing' => $this->totalOutgoing,
                'totalBoth' => $this->totalBoth,
                'totalAgent' => $this->totalAgent,
                'totalCustUnAns' => $this->totalCustUnAns,
                'totalCustAns' => $this->totalCustAns,
                'tab_count' => array(
                    '#total_leads_count' => $this->totalAvailable,
                    '#complete_leads_count' => $this->totalCompleted,
                    '#follow_leads_count' => $this->totalFollow,
                ),

            ]);
        }


        $this->html = $builder->columns([
            ['data' => 'id', 'name' => 'id', 'title' => 'Id'],
            ['data' => 'lead.client_name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'lead.mobile', 'name' => 'Phone', 'title' => 'Phone'],
            ['data' => 'action', 'name' => 'action', 'title' => 'action'],

        ])->setTableId('tab-table');



        return view('admin.lead.dashboard', $this->data);
    }

    function getLeads()
    {

        $this->totalAvailable = CampaignLead::where('campaign_leads.status', 0)->count();

        $this->totalCompleted = CampaignLead::where('campaign_leads.status', 1)->count();

        $this->totalFollow = CampaignLead::where('campaign_leads.status', 2)->count();

        $this->callPurposes = CallPurpose::all();
        return $this->data;
    }


    public function callingLeadDetails(Request $request)
    {



        if ($request->type === 'lead') {


            $this->lead = Lead::find($request->log_id);

            $log = new ManualLoggedCall();
            $log->lead_id = $this->lead->id;
            $log->lead_number = $this->lead->mobile;
            $log->agent_number = empty(auth()->user()->sip_user) ? auth()->user()->mobile : auth()->user()->sip_user;
            $log->created_by = auth()->user()->id;
            $log->call_status = 0;
            $log->call_source = 1;
            $log->status = 0;
            $log->campaign_id = $request->campaign_id;
            $log->session_id =  uniqid();
            $log->call_type = isset($request->call_type) ? $request->call_type : 0;
            $log->save();
            $this->log = ManualLoggedCall::find($log->id);
            $this->mobile = trim($this->lead->mobile);


            $this->calls = ManualLoggedCall::where('lead_number', '123')
                ->where('status', 0)
                ->orderBy('id', 'desc')
                ->first();

            $this->recent_calls = ManualLoggedCall::where('lead_id', $this->lead->id)->get();
            // $this->callperposes = CustomFieldGroup::where('model', 'App\ManualLoggedCall')->get();
            $this->callperposes = CallPurpose::all();
            $this->type = 'call';
            $this->isManual = true;
        } else {
            $this->log = ManualLoggedCall::find($request->log_id);
            $this->mobile = trim($this->log->lead_number);

            $this->lead = Lead::find($this->log->lead_id);
            $this->calls = ManualLoggedCall::where('lead_number', '123')
                ->where('status', 0)
                ->orderBy('id', 'desc')
                ->first();

            $this->recent_calls = ManualLoggedCall::where('lead_number', $this->log->lead_number)->get();
            // $this->callperposes = CustomFieldGroup::where('model', 'App\ManualLoggedCall')->get();
            $this->callperposes = CallPurpose::all();
            $this->type = 'call';
        }

        return view('admin.lead.incoming', $this->data);
    }


    public function storeLoggedCallDetails(Request $request)
    {
        $callP = CallPurpose::find($request->call_purpose);
        $log = ManualLoggedCall::find($request->log_id);
        $log->call_source = $request->call_source;
        $log->call_purpose = $request->call_purpose;
        $log->call_outcome_id = $request->call_outcome_id;
        $log->campaign_lead_status_id = $request->campaign_lead_status_id;
        $log->duration = $request->duration;
        $log->call_status = $request->call_status;
        $log->save();
        if ($request->get('custom_fields_data')) {
            $this->updateCustomFieldData($request->get('custom_fields_data'), $callP->id);
        }
        return Reply::successWithData('Call Log entry Completed', ['data' => $request->all()]);
    }

    public function getImport()
    {

        // return Excel::download(new LeadsExport,'leads.xlsx');

        return view('admin.lead.import', $this->data);
    }

    // public function parseImport(CsvImportRequest $request)
    // {

    //     $path = $request->file('csv_file')->getRealPath();

    //     if ($request->has('header')) {
    //         $data = Excel::import($path, function($reader) {})->get()->toArray();
    //     } else {
    //     $data = array_map('str_getcsv', file($path));
    //     }

    //     if (count($data) > 0) {
    //         if ($request->has('header')) {
    //             $this->csv_header_fields = [];
    //             foreach ($data[0] as $key => $value) {
    //                 $csv_header_fields[] = $key;
    //             }
    //         }
    //         $this->csv_data = array_slice($data, 0, 2);

    //         $this->csv_data_file = CsvData::create([
    //             'csv_filename' => $request->file('csv_file')->getClientOriginalName(),
    //             'csv_header' => $request->has('header'),
    //             'csv_data' => json_encode($data)
    //         ]);
    //     } else {
    //         return redirect()->back();
    //     }

    //     return view('admin.lead.import_fields', $this->data);
    // }

    public function parseImport(CsvImportRequest $request)
    {

        $path = $request->file('csv_file')->getRealPath();

        if ($request->has('header')) {
            $data = Excel::load($path, function ($reader) {
            })->get()->toArray();
        } else {
            $data = array_map('str_getcsv', file($path));
        }

        if (count($data) > 0) {
            if ($request->has('header')) {
                $csv_header_fields = [];
                foreach ($data[0] as $key => $value) {
                    $csv_header_fields[] = $key;
                }
            }
            $this->csv_data = array_slice($data, 0, 2);

            $this->csv_data_file = CsvData::create([
                'csv_filename' => $request->file('csv_file')->getClientOriginalName(),
                'csv_header' => $request->has('header'),
                'csv_data' => json_encode($data)
            ]);
        } else {
            return redirect()->back();
        }

        return view('admin.lead.import_fields', $this->data);
    }



    public function processImport(Request $request)
    {
        $data = CsvData::find($request->csv_data_file_id);
        $csv_data = json_decode($data->csv_data, true);

        dd($csv_data);

        // dd($request->fields);

        foreach ($csv_data as $row) {
            $contact = new Lead();
            foreach ($request->fields as $index => $field) {

                dd($row);
                // if ($data->csv_header) {
                //     $contact->$field = $row[$request->fields[$field]];
                //     return "ok";
                // } else {
                //     $contact->$field = $row[$request->fields[$index]];

                //     return "1";
                // }

                $contact->$field = $row[$field];
                $contact->company_id = company()->currency_id;
            }
            $contact->save();
        }
    }

    public function export()
    {
        return Excel::download(new LeadsExport, 'leads.xlsx');
    }

    public function import(Request $request)
    {


        $rows = $request->file->store('/upload/execl/');
        dispatch(new AdminLeadImportJob($rows));






        return back()->withMessage('file successfully imported');
    }
}
