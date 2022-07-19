<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Controllers\Controller;
use App\IvrGreeting;
use Illuminate\Http\Request;

class IvrGreetingsController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageIcon = 'icon-list';
        $this->pageTitle = 'IVR Greetings';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->greetings = IvrGreeting::all();
        return view('admin.ivr-greetings.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.ivr-greetings.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'audio_clip' => 'required|mimes:audio/mpeg,mpga,mp3,wav,aac'
        ]);
        $greetings = new IvrGreeting();
        $greetings->name = $request->name;
        $greetings->audio_clip = $request->audio_clip->store('audio/greetings');
        $greetings->save();
        return Reply::redirect(route('admin.ivr-greetings.index'), 'IVR Greeting created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->greetings = IvrGreeting::findOrFail($id);
        return view('admin.ivr-greetings.edit', $this->data);
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
        $this->validate($request, [
            'name' => 'required',
        ]);
        $greetings = IvrGreeting::findOrFail($id);
        $greetings->name = $request->name;
        if ($request->has('file')) {
            $greetings->audio_clip = $request->audio_clip->store('audio/greetings');
        }
        $greetings->save();
        return Reply::redirect(route('admin.ivr-greetings.index'), 'IVR Greeting updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $greetings = IvrGreeting::findOrFail($id);
        $greetings->delete();
        return redirect()->route('admin.ivr-greetings.index')->with('messages', 'IVR Greeting deleted successfully.');
    }
}
