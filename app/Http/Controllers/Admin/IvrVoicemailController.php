<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Controllers\Controller;
use App\VoiceMail;
use App\Team;
use Illuminate\Http\Request;

class IvrVoicemailController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageIcon = 'icon-list';
        $this->pageTitle = 'IVR Voicemail';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->voicemails = VoiceMail::all();
        return view('admin.ivr-voicemail.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->departments = Team::all();
        return view('admin.ivr-voicemail.create', $this->data);
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
        $voicemails = new VoiceMail();
        $voicemails->name = $request->name;
        $voicemails->type = $request->type;
        if($request->department=='department'){
            $voicemails->department_id = $request->department;
        }else{
            $voicemails->department_id = null;
        }
        
        $voicemails->audio_clip = $request->audio_clip->store('audio/voicemails');
        $voicemails->save();
        return Reply::redirect(route('admin.ivr-voicemail.index'), 'IVR Voicemail created successfully.');
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
        $this->departments = Team::all();

        $this->voicemails = VoiceMail::findOrFail($id);
        return view('admin.ivr-voicemail.edit', $this->data);
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
        $voicemails = VoiceMail::findOrFail($id);
        $voicemails->name = $request->name;
        if ($request->has('file')) {
            $voicemails->audio_clip = $request->audio_clip->store('audio/voicemails');
        }
        if($request->department=='department'){
            $voicemails->department_id = $request->department;
        }else{
            $voicemails->department_id = null;
        }
        $voicemails->save();
        return Reply::redirect(route('admin.ivr-voicemail.index'), 'IVR Voicemail updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $voicemails = VoiceMail::findOrFail($id);
        $voicemails->delete();
        return redirect()->route('admin.ivr-voicemail.index')->with('messages', 'IVR Voicemail deleted successfully.');
    }
}
