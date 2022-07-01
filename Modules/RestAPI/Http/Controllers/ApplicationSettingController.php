<?php

namespace Modules\RestAPI\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\Admin\AdminBaseController;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Modules\RestAPI\Entities\ApplicationSetting;
use Yajra\DataTables\Facades\DataTables;

class ApplicationSettingController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'restapi::app.menu.applicationSettings';
        $this->pageIcon = 'icon-settings';
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $restAPISetting = ApplicationSetting::all();
        $data = DataTables::of($restAPISetting)
            ->editColumn('name', function ($row) {
                    return $row->name;
            })
            ->editColumn('status', function ($row) {
                    return ucfirst($row->status);
            })
            ->addColumn('action', function ($row) {
                    $action = '<div class="btn-group dropdown m-r-10">
                <button 
                    aria-expanded="false" 
                    data-toggle="dropdown" 
                    class="btn btn-default dropdown-toggle waves-effect waves-light" 
                    type="button">
                    <i class="fa fa-gears "></i>
                </button>
                <ul role="menu" class="dropdown-menu pull-right">
                    <li>
                        <a href="javascript:;" data-application-id="' . $row->id . '"  class="edit-application">
                            <i class="fa fa-pencil" aria-hidden="true"></i> ' . trans('app.edit') . '
                        </a>
                    </li>
                    <li>
                        <a href="javascript:;" onclick="showSecret('.$row->id.')" class="regenerateSecret">
                            <i class="fa fa-refresh" aria-hidden="true"></i> ' .
                            trans('restapi::modules.applicationSettings.regenerateSecret') . ' 
                        </a>
                    </li>
                    <li>
                        <a href="javascript:;" data-application-id="' . $row->id . '" class="sa-params">
                            <i class="fa fa-times" aria-hidden="true"></i> ' . trans('app.delete') . '
                        </a>
                    </li>';

                    $action .= '</ul> </div>';

                    return $action;
            })
            ->rawColumns(['name', 'status', 'action'])
            ->make(true);
        return $data;
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('restapi::admin.application-setting.create', $this->data);
    }

    /**
     * @param Request $request
     * @param $id
     * @return Application|Factory|View
     */
    public function edit(Request $request, $id)
    {
        $this->applicationSetting = ApplicationSetting::find($id);
        return view('restapi::admin.application-setting.edit', $this->data);
    }

    /**
     * @param Request $request
     * @param $id
     * @return array|string[]
     */
    public function show(Request $request, $id)
    {
        //
        return Reply::redirect(route('admin.rest-api-setting.index'), __('messages.settingsUpdated'));
    }

    /**
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        $applicationSetting = new ApplicationSetting();
        $applicationSetting->name = $request->name;
        $applicationSetting->save();

        $this->applicationSetting = $applicationSetting;

        return Reply::successWithData(__('messages.settingsUpdated'), $this->data);
    }

    /**
     * @param Request $request
     * @param $id
     * @return array
     */
    public function update(Request $request, $id)
    {
        $applicationSetting = ApplicationSetting::find($id);
        $applicationSetting->name = $request->name;
        $applicationSetting->save();

        $this->applicationSetting = $applicationSetting;

        return Reply::successWithData(__('messages.settingsUpdated'), $this->data);
    }

    /**
     * @param $id
     * @return array
     */
    public function destroy($id)
    {
        ApplicationSetting::destroy($id);
        return Reply::success('messages.deleteSuccess');
    }

    /**
     * @param $id
     * @return mixed
     */
    public function regenerateSecret($id)
    {
        $application = ApplicationSetting::find($id);

        $secret = str_random(60);

        $application->app_secret = $secret;
        $application->save();

        $this->app_key = $application->app_key;
        $this->app_secret = $secret;
        $this->status = 'success';

//        return Reply::dataOnly($this->data);
        return view('restapi::admin.application-setting.show-secret', $this->data);
    }
}
