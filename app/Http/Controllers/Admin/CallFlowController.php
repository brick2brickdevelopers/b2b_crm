<?php

namespace App\Http\Controllers\Admin;

use App\CallFlowDiagram;
use App\Helper\Reply;
use App\Http\Controllers\Controller;
use App\IvrGreeting;
use App\VoiceMail;
use Illuminate\Http\Request;

class CallFlowController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageIcon = 'icon-list';
        $this->pageTitle = 'Call Flow Design';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->call_flow_diagrams = CallFlowDiagram::all();
        return view('admin.call-flow-design.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->grettings = IvrGreeting::all();
        $this->voicemails = VoiceMail::all();
        return view('admin.call-flow-design.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $empty_array = [];

        $call_flow_design = new CallFlowDiagram();
        $call_flow_design->company_id = company()->id;
        $call_flow_design->name = $request->name;
        $call_flow_design->greetings_id = $request->greetings_id;
        // $call_flow_design->menu = $request->menu;
        $call_flow_design->menu = $request->has('menu') ? $request->menu : 0;
        $call_flow_design->menu_message = $request->menu_message;
        $call_flow_design->extensions = $request->has('extensions') ? json_encode($request->extensions) : json_encode(array('num' => $request->num, 'ext' => $request->voice));
        $call_flow_design->voicemail = $request->has('voicemail') ? $request->voicemail : 0;;
        $call_flow_design->non_working_hours = $request->has('non_working_hours') ? $request->non_working_hours : 0;
        $call_flow_design->start_time = $request->start_time;
        $call_flow_design->end_time = $request->end_time;
        $call_flow_design->non_working_hours_greetings = $request->non_working_hours_greetings;
        $call_flow_design->non_working_hours_voicemail = $request->non_working_hours_voicemail;
        $call_flow_design->non_working_days = $request->has('non_working_days') ? $request->non_working_days : 0;
        $call_flow_design->days = $request->has('days') ? json_encode($request->days) : json_encode($empty_array);
        $call_flow_design->non_working_days_greetings = $request->non_working_days_greetings;
        $call_flow_design->non_working_days_voicemail = $request->non_working_days_voicemail;
        // return($request->all());
        //dd($request->all());
        $call_flow_design->save();
        return Reply::redirect(route('admin.call-flow-design.index'), 'Calling Group created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->grettings = IvrGreeting::all();
        $this->voicemails = VoiceMail::all();
        $this->call_flow_diagram = CallFlowDiagram::findOrFail($id);

        //return($this->call_flow_diagram);

        return view('admin.call-flow-design.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $call_flow_design =  CallFlowDiagram::findOrFail($id);

        $call_flow_design->company_id = company()->id;
        $call_flow_design->name = $request->name;
        $call_flow_design->greetings_id = $request->greetings_id;
        // $call_flow_design->menu = $request->menu;
        $call_flow_design->menu = $request->has('menu') ? $request->menu : 0;
        $call_flow_design->menu_message = $request->menu_message;
        $call_flow_design->extensions = $request->has('extensions') ? json_encode($request->extensions) : json_encode(array('num' => $request->num, 'ext' => $request->voice));
        $call_flow_design->voicemail = $request->has('voicemail') ? $request->voicemail : 0;;
        $call_flow_design->non_working_hours = $request->has('non_working_hours') ? $request->non_working_hours : 0;
        $call_flow_design->start_time = $request->start_time;
        $call_flow_design->end_time = $request->end_time;
        $call_flow_design->non_working_hours_greetings = $request->non_working_hours_greetings;
        $call_flow_design->non_working_hours_voicemail = $request->non_working_hours_voicemail;
        $call_flow_design->non_working_days = $request->has('non_working_days') ? $request->non_working_days : 0;
        $call_flow_design->days = $request->has('days') ? json_encode($request->days) : array();
        $call_flow_design->non_working_days_greetings = $request->non_working_days_greetings;
        $call_flow_design->non_working_days_voicemail = $request->non_working_days_voicemail;
        //return($request->all());
        //dd($request->all());
        $call_flow_design->update();
        return Reply::redirect(route('admin.call-flow-design.index'), 'Calling Group created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // CallFlowDiagram::destroy($id);

        $callFlowDiagram =  CallFlowDiagram::find($id);
        $callFlowDiagram->delete();

        return back();
    }
}
