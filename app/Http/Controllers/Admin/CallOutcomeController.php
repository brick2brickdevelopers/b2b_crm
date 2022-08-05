<?php

namespace App\Http\Controllers\Admin;


use App\CallOutcome;
use App\Helper\Reply;
use Illuminate\Http\Request;

class CallOutcomeController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageIcon = 'icon-list';
        $this->pageTitle = 'Call Outcome Group';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->callOutcomes = CallOutcome::all();
        return view('admin.call-outcome.index', $this->data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.call-outcome.create', $this->data);

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
        
        $callOutcome = new CallOutcome();
        $callOutcome->name = $request->name;
        $callOutcome->company_id = company()->id;


        $callOutcome->save();

        return Reply::redirect(route('admin.call-outcome.index'), __('messages.groupUpdatedSuccessfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CallOutcome  $callOutcome
     * @return \Illuminate\Http\Response
     */
    public function show(CallOutcome $callOutcome)
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
      
        $this->callOutcome = CallOutcome::findOrFail($id);
        return view('admin.call-outcome.edit', $this->data);
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
        $callOutcome = CallOutcome::find($id);
        $callOutcome->name = $request->name;

        $callOutcome->save();
        return Reply::redirect(route('admin.call-outcome.index'), __('messages.groupUpdatedSuccessfully'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CallOutcome  $callOutcome
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $callOutcome = CallOutcome::findOrFail($id);
       
        $callOutcome->delete();
        return redirect()->route('admin.call-outcome.index')->with('success', 'Call Outcome Deleted Successfully');
    }
}
