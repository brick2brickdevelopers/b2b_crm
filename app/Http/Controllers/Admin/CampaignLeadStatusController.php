<?php

namespace App\Http\Controllers\Admin;

use App\CampaignLeadStatus;
use App\Helper\Reply;
use Illuminate\Http\Request;

class CampaignLeadStatusController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageIcon = 'icon-list';
        $this->pageTitle = 'Campaign Lead Status Group';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $this->callOutcomes = CampaignLeadStatus::all();
        return view('admin.campaign-lead-status.index', $this->data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.campaign-lead-status.create', $this->data);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required",
        ]);
        
        $callOutcome = new CampaignLeadStatus();
        $callOutcome->name = $request->name;
        $callOutcome->company_id = company()->id;


        $callOutcome->save();

        return Reply::redirect(route('admin.campaign-lead-status.index'), __('messages.groupUpdatedSuccessfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CallOutcome  $callOutcome
     * @return \Illuminate\Http\Response
     */
    public function show(CampaignLeadStatus $campaignLeadStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CallOutcome  $callOutcome
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      
        $this->callOutcome = CampaignLeadStatus::findOrFail($id);
        return view('admin.campaign-lead-status.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CallOutcome  $callOutcome
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $callOutcome = CampaignLeadStatus::find($id);
        $callOutcome->name = $request->name;

        $callOutcome->save();
        return Reply::redirect(route('admin.campaign-lead-status.index'), __('messages.groupUpdatedSuccessfully'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CallOutcome  $callOutcome
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $callOutcome = CampaignLeadStatus::findOrFail($id);
       
        $callOutcome->delete();
        return redirect()->route('admin.campaign-lead-status.index')->with('success', 'Campaign lead status Deleted Successfully');
    }
}
